# hostel-app/config.py
import os

DEBUG = True
SECRET_KEY = os.environ['SECRET_KEY']
SQLALCHEMY_ECHO = True
SQLALCHEMY_DATABASE_URI = os.environ['DATABASE_URL']
SQLALCHEMY_TRACK_MODIFICATIONS = False
SECURITY_PASSWORD_SALT = os.environ['SECURITY_PASSWORD_SALT']