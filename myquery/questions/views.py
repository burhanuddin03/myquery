from django.shortcuts import render
import datetime
from django.views.generic import ListView,DeleteView,DetailView
from django.contrib.auth.decorators import login_required
from .models import Categorie,Tag,Question,files,Answer,AnswerImage,Replie,Like,Dislike
from django.urls import reverse_lazy
from django.shortcuts import render,redirect


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
        return render(request,'post question.html',{'Cats':Cat})

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
            print("a")
            obj2 = Answer.objects.get(pk=int(Ans))
            i=Like.objects.filter(Answer=obj2,User=request.user).exists()
            # print(i)
            j=Dislike.objects.filter(Answer=obj2,User=request.user).exists()
            if i:
                ob = Like.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Like_count-=1
                obj2.save()
            elif j:
                ob = Dislike.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Like_count+=1
                obj2.Dislike_count-=1
                obj2.save()
                obj = Like(Answer=obj2,User=request.user)
                obj.save()
            else:
                obj2.Like_count+=1
                obj2.save()
                obj = Like(Answer=obj2,User=request.user)
                obj.save()
            print("abcd")
            return redirect("questions:qd",pk=self.kwargs.get("pk"))
        elif 'dislike' in request.POST:
            Ans = request.POST['dislike']
            obj2 = Answer.objects.get(pk=int(Ans))
            i=Like.objects.filter(Answer=obj2,User=request.user).exists()
            # print(i)
            j=Dislike.objects.filter(Answer=obj2,User=request.user).exists()
            if j:
                ob = Dislike.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Dislike_count-=1
                obj2.save()
            elif i:
                ob = Like.objects.get(Answer=obj2,User=request.user)
                ob.delete()
                obj2.Like_count-=1
                obj2.Dislike_count+=1
                obj2.save()
                obj = Dislike(Answer=obj2,User=request.user)
                obj.save()
            else:
                obj2.Dislike_count+=1
                obj2.save()
                obj = Dislike(Answer=obj2,User=request.user)
                obj.save()
            return redirect("questions:qd",pk=self.kwargs.get("pk"))
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