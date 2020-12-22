from django.db import models
from PIL import Image
from myquery import settings
from django.utils import timezone

# Create your models here.
class Categorie(models.Model):
    Categorie_name = models.CharField(max_length=250,blank=False,unique=True)

    def __str__(self):
        return self.Categorie_name

class Tag(models.Model):
    Tag_name = models.CharField(max_length=250,blank=False,unique=True)
    
    def __str__(self):
        return self.Tag_name

class Question(models.Model):
    User = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
    Title=models.CharField(max_length=250,blank=False)
    Categorie=models.ForeignKey(Categorie,on_delete=models.CASCADE)
    Tags = models.ManyToManyField(Tag)
    Details=models.TextField(blank=False, null=False,default="nothing")
    Qcreated=models.DateTimeField(editable=False)

    def __str__(self):
        return self.Title

class files(models.Model):
    File = models.FileField(upload_to='uploads/',blank=True, null=True)
    Question = models.ForeignKey(Question,on_delete=models.CASCADE)
    def __str__(self):
        return self.File.url
    def helper(self):
        try:
            urls = Image.open(self.File)
            print(urls)
            return True
        except:
            return False 


class Answer(models.Model):
    User = models.ForeignKey(settings.AUTH_USER_MODEL,on_delete=models.CASCADE,null=True)
    Question = models.ForeignKey(Question,on_delete=models.CASCADE,null=True)
    Details = models.TextField(blank=False,null=False,default="nothing")
    Acreated = models.DateTimeField(editable=False,default=timezone.now)
    Like_count = models.IntegerField(blank=True,default=0)
    Dislike_count = models.IntegerField(blank=True,default=0)
    Corrected = models.BooleanField(blank=True,default=0)

    def __str__(self):
        return self.Details


class AnswerImage(models.Model):
    Image = models.ImageField(upload_to='Ansuploads/',blank=True,null=True)
    Answer = models.ForeignKey(Answer,on_delete=models.CASCADE)

    def __str__(self):
        return self.Image.url

class Replie(models.Model):
    Answer = models.ForeignKey(Answer,on_delete=models.CASCADE)
    User = models.ForeignKey(settings.AUTH_USER_MODEL,on_delete=models.CASCADE)
    Details = models.TextField(blank=False,null=False,default="nothing")
    Rcreated = models.DateTimeField(editable=False,default=timezone.now)

    def __str__(self):
        return self.Details

class Like(models.Model):
    Answer = models.ForeignKey(Answer,on_delete=models.CASCADE)
    User = models.ForeignKey(settings.AUTH_USER_MODEL,on_delete=models.CASCADE)

    def __str__(self):
        return self.User.username

class Dislike(models.Model):
    Answer = models.ForeignKey(Answer,on_delete=models.CASCADE)
    User = models.ForeignKey(settings.AUTH_USER_MODEL,on_delete=models.CASCADE)

    def __str__(self):
        return self.User.username