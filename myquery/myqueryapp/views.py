from django.shortcuts import render
from django.contrib.auth import get_user_model
from django.contrib.messages.views import SuccessMessageMixin
from django.contrib.auth.mixins import LoginRequiredMixin
from django.urls import reverse_lazy
from .forms import UserRegisterForm
from django.contrib.auth import get_user_model
from django.views.generic import CreateView
from questions.models import Question
from .models import User


# Create your views here.
def index(request):
    AllQuestion = Question.objects.all().order_by('-Qcreated')
    return render(request,"index.html",{'All':AllQuestion})
    
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
