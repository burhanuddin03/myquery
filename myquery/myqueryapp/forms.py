from django.contrib.auth.forms import UserCreationForm,AuthenticationForm,UsernameField
from django import forms
from django.contrib.auth import get_user_model

class CustomLoginForm(AuthenticationForm):
	username = forms.CharField(
		label='',
		widget=forms.TextInput(attrs={'class':'form-control','style':'width:100%;height:100%','placeholder':'Username'})
	)
	password=forms.CharField(
		label="",
		strip=False ,
		widget=forms.PasswordInput(attrs={'class':'form-control','style':'width:100%;height:100%','placeholder':'Password'}),
	)

class UserRegisterForm(UserCreationForm):

	class Meta:
		model=get_user_model()
		fields=('username','email','password1','password2','profile_pic')
		
		widgets={
			'username':forms.TextInput(attrs={'class':'form-control','style':'width:100%;height:100%','placeholder':'UserName','id':'user_username'}),
			'email':forms.EmailInput(attrs={'class':'form-control','placeholder':'Email','id':'user_email'}),
			'password1':forms.PasswordInput(attrs={'class':'form-control','placeholder':'Password','id':'user_userpass'}),
			'password2':forms.PasswordInput(attrs={'class':'form-control','placeholder':'Password','id':'user_userpassreenter'}),
			 'profile_pic':forms.FileInput(attrs={'id':'user_userimage'})
		}

	def clean_username(self):
		username=self.cleaned_data['username']
		print(username)
		if get_user_model().objects.filter(username__iexact=username).exists():
			raise forms.ValidationError('User already exist with this username')
		return username 
	
	
	def __init__(self,*args,**kwargs):
		super().__init__(*args,**kwargs)
		self.fields['profile_pic'].label='Image'
		self.fields['email'].label='Email'
		self.fields['username'].label='UserName'
		self.fields['password1'].label='Password'
		self.fields['password2'].label='Re-enter Password'
		self.fields['password1'].help_text=None
		
	
	def save(self,commit=True):
		user=super(UserRegisterForm,self).save(commit=False)
		if 'profile_pic' in self.cleaned_data:
			user.profile_pic=self.cleaned_data['profile_pic']
		if commit:
			user.save()
		return user
