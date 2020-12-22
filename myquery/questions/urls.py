from django.urls import path
from . import views

app_name='questions'

urlpatterns = [
  path('',views.PostQuestionView,name="postquestion"),
  path('question/<int:pk>/',views.qdView.as_view(),name="qd"),
]