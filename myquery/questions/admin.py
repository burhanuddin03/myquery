from django.contrib import admin
from . import models
# Register your models here.
admin.site.register(models.Categorie)
admin.site.register(models.Tag)
admin.site.register(models.Question)
admin.site.register(models.files)
admin.site.register(models.Answer)
admin.site.register(models.AnswerImage)
admin.site.register(models.Replie)
admin.site.register(models.Like)
admin.site.register(models.Dislike)