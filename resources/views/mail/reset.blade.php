<!doctype html>
<html>
 <head>
  <meta charset="utf-8">
 </head>
 <body> 
  <p>{{$user->name}}, если вы получили это письмо, то у вас плохая память, сочувствую.</p>
  <p>Пройдите по ссылке ниже, чтобы восстановить пароль.</p>
  <a href="{{ url('http://property.test/password/reset/' . $token . '?email=' . $user->email) }}"
    style="display: inline-block;
        background: #4b0082;
        color: #fff;
        padding: 1rem 1.5rem;
        text-decoration: none;
        border-radius: 3px;"
        >
        Восстановить пароль
        </a>
 </body>
</html>