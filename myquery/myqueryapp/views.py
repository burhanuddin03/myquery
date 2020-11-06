from django.shortcuts import render
from django.contrib.auth import get_user_model
from django.contrib.messages.views import SuccessMessageMixin
from django.contrib.auth.mixins import LoginRequiredMixin
from django.urls import reverse_lazy
from .forms import UserRegisterForm
from django.contrib.auth import get_user_model
from django.views.generic import CreateView

# Create your views here.
def index(request):
	return render(request,"index.html")

class UserRegisterView(SuccessMessageMixin,CreateView):
	template_name="signup.html"
	form_class=UserRegisterForm
	success_url=reverse_lazy('myqueryapp:register')
	success_message="%(username)s's account was Successfully Created!"
