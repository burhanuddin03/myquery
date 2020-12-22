# Generated by Django 3.0.6 on 2020-12-18 16:30

from django.conf import settings
from django.db import migrations, models
import django.db.models.deletion
import django.utils.timezone


class Migration(migrations.Migration):

    dependencies = [
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
        ('questions', '0004_useranswers'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='useranswers',
            name='Question',
        ),
        migrations.RemoveField(
            model_name='useranswers',
            name='User',
        ),
        migrations.RemoveField(
            model_name='image',
            name='name',
        ),
        migrations.AddField(
            model_name='image',
            name='Acreated',
            field=models.DateTimeField(default=django.utils.timezone.now, editable=False),
        ),
        migrations.AddField(
            model_name='image',
            name='Details',
            field=models.TextField(default='nothing'),
        ),
        migrations.AddField(
            model_name='image',
            name='Question',
            field=models.ForeignKey(null=True, on_delete=django.db.models.deletion.CASCADE, to='questions.Question'),
        ),
        migrations.AddField(
            model_name='image',
            name='User',
            field=models.ForeignKey(null=True, on_delete=django.db.models.deletion.CASCADE, to=settings.AUTH_USER_MODEL),
        ),
        migrations.DeleteModel(
            name='Answers',
        ),
        migrations.DeleteModel(
            name='UserAnswers',
        ),
    ]