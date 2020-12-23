from django.urls import path
from django.contrib.auth.views import LoginView,LogoutView
from . import views
from .forms import CustomLoginForm
from questions import views as vw
from django.views.generic.base import TemplateView

app_name='myqueryapp'

urlpatterns = [
    path('',views.index,name="index"),
    path('password_reset/',views.PasswordResetClassView.as_view(),name="password_reset"),
    path('password_reset_confirm/<uidb64>/<token>',views.PasswordConfirmClassView.as_view(),name='password_reset_confirm'),
    path('password_reset/done/',TemplateView.as_view(template_name='Password_reset/password_reset_done.html'),name='password_reset_done'),
    path('password_reset_confirm/done/',TemplateView.as_view(template_name='Password_reset/password_reset_confirm_done.html'),name='password_reset_confirm_done'),
    path('Popular-Resposes/',views.index2,name="index2"),
    path('Recently-Answered/',views.index3,name="index3"),
    path('No-Answers/',views.index4,name="index4"),
    path('login/',LoginView.as_view(template_name="login.html",form_class=CustomLoginForm),name="login"),
    path('logout/',LogoutView.as_view(),name='logout'),
    path('register/',views.UserRegisterView.as_view(),name="register"),
    path('contactus/',views.ContactView,name="contactus"),
    path('HowItWorks/',views.HowitWorksView,name="how"),
    path('Dashboard/',vw.DashboardView,name="dashboard"),
    path('profile-update/',views.Update,name="update"),
    path('search-results/',vw.SearchListView.as_view(),name="search"),
]
 