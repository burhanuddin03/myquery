from django.urls import path
from . import views

app_name='questions'

urlpatterns = [
  path('',views.PostQuestionView,name="postquestion"),
  path('question/<int:pk>/',views.qdView.as_view(),name="qd"),
  path('DeleteQuestion/<int:pk>/',views.DeleteQuestionView.as_view(),name="question-delete"),
  path('Question/<int:apk>/DeleteAnswer/<int:pk>/',views.DeleteAnswerView.as_view(),name="delete-answer"),
  path('Question/<int:apk>/DeleteReply/<int:pk>/',views.DeleteReplyView.as_view(),name="delete-reply"),
]