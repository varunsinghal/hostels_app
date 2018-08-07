from flask import Flask, url_for, session, request
from flask_sqlalchemy import SQLAlchemy
from werkzeug.utils import redirect

app = Flask(__name__)
app.config.from_pyfile('config.py')

db = SQLAlchemy(app)


@app.route("/")
def app_index():
    return redirect(url_for('home.index'))


@app.before_request
def before_request():
    if 'username' not in session and not request.endpoint.startswith('public'):
        return redirect(url_for('public.index'))


if __name__ == '__main__':
    from views.home import home
    from views.public import public
    from views.user import user

    app.register_blueprint(public, url_prefix='/public')
    app.register_blueprint(home, url_prefix='/home')
    app.register_blueprint(user, url_prefix='/user')
    app.run()