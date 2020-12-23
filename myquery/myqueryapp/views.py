from django.shortcuts import render
from django.contrib.auth import get_user_model
from django.contrib.messages.views import SuccessMessageMixin
from django.contrib.auth.mixins import LoginRequiredMixin
from django.urls import reverse_lazy
from .forms import UserRegisterForm
from django.contrib.auth import get_user_model
from django.views.generic import CreateView
from questions.models import Question,Answer
from .models import User
from django.db.models import Count
from django.contrib.auth.views import PasswordResetView,PasswordResetConfirmView
from .forms import CustomPasswordResetForm,PasswordConfirmForm


# Create your views here.
def index(request):
    AllQuestion = Question.objects.all().order_by('-Qcreated')
    return render(request,"index.html",{'All':AllQuestion,'index1':"Hello"})

def index2(request):
    AllQuestion = Question.objects.annotate(count=Count('answer')).order_by('-count')
    return render(request,"index.html",{'All':AllQuestion,'index2':"Hello"})

def index3(request):
    AllAnswer = Answer.objects.select_related('Question').order_by('-Acreated')
    AllQuestion=[]
    for i in AllAnswer:
        AllQ = Question.objects.get(pk=i.Question.pk)
        if AllQ in AllQuestion:
            pass
        else:    
            AllQuestion.append(AllQ)
    return render(request,"index.html",{'All':AllQuestion,'index3':"Hello"})

def index4(request):
    AllQuestion = Question.objects.annotate(count=Count('answer')).filter(count=0)
    return render(request,"index.html",{'All':AllQuestion,'index4':"Hello"})
    
def ContactView(request):
    return render(request,"contact.html")

def HowitWorksView(request):
    return render(request,"how it works.html")

def Update(request):
    if request.method=='POST':
        if 'change-image' in request.POST:
            image = request.FILES['Image1']
            obj = User.objects.get(pk=request.user.pk)
            obj.profile_pic = image
            print(image)
            obj.save()
            return render(request,"profile-setting.html")
        else:
            username = request.POST['user']
            email = request.POST['email']
            pw = request.POST['pass']
            cpw = request.POST['confirm']
            if pw==cpw:
                obj = User.objects.get(pk=request.user.pk)
                obj.username = username
                obj.email = email
                obj.set_password(pw)
                obj.save()
                return render(request,"profile-setting.html",{'success':"Profile Update Successfully"})
            else:
                return render(request,"profile-setting.html",{'success':"Passwords Dont match!"})


    return render(request,"profile-setting.html")

class UserRegisterView(SuccessMessageMixin,CreateView):
    template_name="signup.html"
    form_class=UserRegisterForm
    success_url=reverse_lazy('myqueryapp:register')
    success_message="%(username)s's account was Successfully Created!"

class PasswordResetClassView(PasswordResetView):
    template_name='Password_reset/password_reset_form.html'
    email_template_name='Password_reset/password_reset_email.html'
    subject_template_name='Password_reset/password_reset_subject.txt'
    success_url=reverse_lazy('myqueryapp:password_reset_done')
    form_class=CustomPasswordResetForm

class PasswordConfirmClassView(PasswordResetConfirmView):
    template_name='Password_reset/password_reset_confirm.html'
    success_url=reverse_lazy('myqueryapp:password_reset_confirm_done')
    form_class=PasswordConfirmForm