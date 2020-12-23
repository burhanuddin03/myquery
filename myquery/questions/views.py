from django.shortcuts import render
import datetime
from django.views.generic import ListView,DeleteView,DetailView
from django.contrib.auth.decorators import login_required
from .models import Categorie,Tag,Question,files,Answer,AnswerImage,Replie,Like,Dislike
from django.urls import reverse_lazy
from django.shortcuts import render,redirect
from django.contrib.auth.mixins import LoginRequiredMixin
from django.http import JsonResponse

# Create your views here.
@login_required
def PostQuestionView(request):
    if request.method=="POST":
        Title=request.POST['title']
        Categorie1 = Categorie.objects.get(pk=request.POST['category'])
        tags=request.POST['tag']
        detail=request.POST['details']
        Files = request.FILES.getlist('files')
        try:
            if request.user.is_authenticated:
                obj = Question(User=request.user,Title=Title,Categorie=Categorie1,Details=detail,Qcreated=datetime.datetime.now())
                obj.save()
                tags=tags.split(",")
                for i in range(len(tags)):
                    p1,created=Tag.objects.get_or_create(Tag_name=tags[i])
                    obj.Tags.add(p1)
                obj.save()
                for f in Files:
                    file_instance = files(File=f,Question=obj)
                    file_instance.save()
            else:
                print("dafa ho")
        except:
            Cat = Categorie.objects.all()
            return render(request,'post question.html',{'Cats':Cat,'message':"fail"})

        Cat = Categorie.objects.all()
        return render(request,'post question.html',{'Cats':Cat})
    else:
        Cat = Categorie.objects.all()
        AllQuestion = Question.objects.all().order_by('-Qcreated')
        AllQuestion = AllQuestion[:5]
        new = list(Answer.objects.order_by('-Acreated'))
        new = new[:7]
        return render(request,'post question.html',{'Cats':Cat,'questions':AllQuestion,'recent':new})

class qdView(DetailView):
    template_name = "questiondetail.html"
    context_object_name='question'
    model=Question
    def get_context_data(self,**kwargs):
        context = super().get_context_data(**kwargs)
        total_files = list(files.objects.filter(Question=context['question']))
        context['files']=total_files
        context['ans'] = list(Answer.objects.filter(Question=context['question']))
        # context['images'] = list(AnswerImage.objects.filter(Answer=context['ans']))
        hotQ=list(Question.objects.order_by('-Qcreated'))
        context['questions']=hotQ[:5]
        new = list(Answer.objects.order_by('-Acreated'))
        context['recent'] = new[:7]
        print(self.request.user.pk)
        print(context['question'].pk)
        if self.request.user.pk == context['question'].User.pk:
            context['correction'] = True
        else:
            context['correction'] = False
        return context

    def post(self, request, *args, **kwargs):
        if 'Answer-button' in request.POST:
            Ans = request.POST['answer']
            Image = request.FILES.getlist('myImage')
            obj1=Question.objects.get(pk=self.kwargs.get("pk"))
            obj = Answer(User=self.request.user,Question=obj1,Details=Ans,Acreated=datetime.datetime.now())
            obj.save()
            for f in Image:
                    img_instance = AnswerImage(Image=f,Answer=obj)
                    img_instance.save()
            return redirect("questions:qd",pk=self.kwargs.get("pk"))
        elif 'like' in request.POST:
            Ans = request.POST['like']
            print(Ans)
            obj2 = Answer.objects.get(pk=int(Ans))
            i=Like.objects.filter(Answer=obj2,User=request.user).exists()
            j=Dislike.objects.filter(Answer=obj2,User=request.user).exists()
            num = 1
            if i:
                ob = Like.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Like_count-=1
                obj2.save()
                num = 1
            elif j:
                ob = Dislike.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Like_count+=1
                obj2.Dislike_count-=1
                obj2.save()
                obj = Like(Answer=obj2,User=request.user)
                obj.save()
                num = 2
            else:
                obj2.Like_count+=1
                obj2.save()
                obj = Like(Answer=obj2,User=request.user)
                obj.save()
                num = 3
            return JsonResponse({'counter':obj2.Like_count,'dislikecounter':obj2.Dislike_count,'num':num})
        elif 'dislike' in request.POST:
            Ans = request.POST['dislike']
            obj2 = Answer.objects.get(pk=int(Ans))
            i=Like.objects.filter(Answer=obj2,User=request.user).exists()
            j=Dislike.objects.filter(Answer=obj2,User=request.user).exists()
            num = 1
            if j:
                ob = Dislike.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Dislike_count-=1
                obj2.save()
                num = 1
            elif i:
                ob = Like.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Like_count-=1
                obj2.Dislike_count+=1
                obj2.save()
                obj = Dislike(Answer=obj2,User=request.user)
                obj.save()
                num =2
            else:
                obj2.Dislike_count+=1
                obj2.save()
                obj = Dislike(Answer=obj2,User=request.user)
                obj.save()
                num = 3
            return JsonResponse({'counter':obj2.Like_count,'dislikecounter':obj2.Dislike_count,'num':num})
        elif 'correct' in request.POST:
            Ans = request.POST['correct']
            obj2 = Answer.objects.get(pk=int(Ans))
            obj2.Corrected = True
            obj2.save()
            return redirect("questions:qd",pk=self.kwargs.get("pk"))
        elif 'uncorrect' in request.POST:
            Ans = request.POST['uncorrect']
            obj2 = Answer.objects.get(pk=int(Ans))
            obj2.Corrected = False
            obj2.save()
            return redirect("questions:qd",pk=self.kwargs.get("pk"))
        else:
            Reply = request.POST['reply-tag']
            Val = request.POST['hide']
            print(Val)
            obj2 = Answer.objects.get(pk=Val)
            obj = Replie(Answer=obj2,User=request.user,Details=Reply,Rcreated=datetime.datetime.now())
            obj.save()
            return redirect("questions:qd",pk=self.kwargs.get("pk"))

@login_required
def DashboardView(request):
    obj = Question.objects.filter(User=request.user)
    print(obj)
    return render(request,"questions.html",{'User_Question':obj})

class SearchListView(ListView):
    template_name='List.html'
    context_object_name='AllQuestion'
    model=Question
    paginate_by=6

    def get_queryset(self):
        search=None
        if 'search' in self.request.GET:
            search=self.request.GET['search']
        else:
            raise Http404()
        search=search.lower()
        Question_query=self.model.objects.filter(Title__icontains=search)
        Question_category_query=self.model.objects.filter(Categorie__Categorie_name__icontains=search)
        Question_tags_query=self.model.objects.filter(Tags__Tag_name__icontains=search)
        return Question_query.union(Question_category_query.union(Question_tags_query)).order_by('-pk')

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context["search"] =self.request.GET['search'] 
        return context

class DeleteQuestionView(LoginRequiredMixin,DeleteView):
    model=Question
    success_url=reverse_lazy("myqueryapp:dashboard")

class DeleteAnswerView(LoginRequiredMixin,DeleteView):
    model=Answer

    def get_success_url(self):
        success_url = reverse_lazy('questions:qd',kwargs={'pk':self.kwargs['apk']})
        return success_url

class DeleteReplyView(LoginRequiredMixin,DeleteView):
    model=Replie

    def get_success_url(self):
        success_url = reverse_lazy('questions:qd',kwargs={'pk':self.kwargs['apk']})
        return success_url
