from django.urls import path
from django.contrib.auth.views import LoginView,LogoutView
from . import views
from .forms import CustomLoginForm

app_name='myqueryapp'

urlpatterns = [
    path('',views.index,name="index"),
    path('login/',LoginView.as_view(template_name="login.html",form_class=CustomLoginForm),name="login"),
    path('logout/',LogoutView.as_view(),name='logout'),
    path('register/',views.UserRegisterView.as_view(),name="register"),
]
 