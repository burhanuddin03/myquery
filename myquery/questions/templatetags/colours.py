from django import template
from questions import models
register=template.Library()
@register.filter(name="colour")
def colour(Us,Ans):
    obj = models.Like.objects.filter(User=Us,Answer=Ans).exists()
    if obj:
        return True
    return False

@register.filter(name="colour1")
def colour1(Us,Ans):
    obj = models.Dislike.objects.filter(User=Us,Answer=Ans).exists()
    if obj:
        return True
    return False