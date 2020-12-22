from django.urls import path
from django.contrib.auth.views import LoginView,LogoutView
from . import views
from .forms import CustomLoginForm
from questions import views as vw

app_name='myqueryapp'

urlpatterns = [
    path('',views.index,name="index"),
    path('login/',LoginView.as_view(template_name="login.html",form_class=CustomLoginForm),name="login"),
    path('logout/',LogoutView.as_view(),name='logout'),
    path('register/',views.UserRegisterView.as_view(),name="register"),
    path('contactus/',views.ContactView,name="contactus"),
    path('HowItWorks/',views.HowitWorksView,name="how"),
    path('Dashboard/',vw.DashboardView,name="dashboard"),
    path('profile-update/',views.Update,name="update"),
]
 