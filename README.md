# passport_test

# commands

```
git clone https://github.com/ashraf-kabir/passport_test
cd passport_test
composer require laravel/passport
php artisan migrate
php artisan passport:install
php artisan serve
```

<br>

# schema (custom models)

users: name, email, password ... timestamps

admins: name, email, password ... timestamps

blogs: title, description, category_id, tag_id, user_id, status, ... timestamps

categories: name, status, ... timestamps

tags: name, status, ... timestamps

<br>

# info

passport multi auth used with guard, scope middlewares.

Two separate api route group created for 2 user roles.

admin cannot view users api route, same goes for users.

admin can register, login& logout.
admin can add categories, tags.
admin can view all blogs, categories, tags & users list
admin can delete categories & tags.

user can register, login & logout.
user can add blogs.
user can view all blogs list.
user can delete only his added blogs.
user can search blogs by blog title, category name, tag name.
user can view his profile details.

on logout token revoked

<br>

# routes

## user:

1. user->register

    method: POST

    ``http://localhost:8000/api/user/register``

    params:

    ``name``
    <br>
    ``email``
    <br>
    ``password``
    <br>
    ``password_confirmation``

    <br>

2. user->login

    method: POST

    ``http://localhost:8000/api/user/login``

    params:

    ``email``
    <br>
    ``password``

    copy token and add on Bearer when necessary

    <br>

3. user->logout

    method: POST

    ``http://localhost:8000/api/user/logout``

    on headers pass Bearer token

    <br>

4. user->blogs->list

    method: GET

    ``http://localhost:8000/api/user/blogs``

    pass Bearer token

    <br>

5. user->blogs->add

    method: POST

    ``http://localhost:8000/api/user/blogs/add``

    params:

    ``title``
    <br>
    ``description``
    <br>
    ``status``
    <br>
    ``category_id``
    <br>
    ``tag_id``

    (title & description is string & text respectively. status, category_id, tag_id are all integer. status can be 1, 0 meant for active, inactive. All params are mandatory here)

    note: on headers pass the Bearer token only. (auto capture user_id from auth after submit)

    <br>

6. user->blogs->delete

    method: GET

    ``http://localhost:8000/api/user/blogs/delete/{id}``

    on headers pass the Bearer token only

    <br>

7. user->blogs->search

    method: GET

    ``http://localhost:8000/api/user/blogs/search``

    params:
    
    ``search_term``

    note: search by blog title, category name & tag name

    on headers pass the Bearer token only

    <br>

8. user->profile details view

    method: GET

    ``http://localhost:8000/api/user/profile``


    on headers pass the Bearer token only

    <br>

## admin:

9. admin->register

    method: POST

    ``http://localhost:8000/api/admin/register``

    params:

    ``name``
    <br>
    ``email``
    <br>
    ``password``
    <br>
    ``password_confirmation``

    <br>

10. admin->login

    method: POST

    ``http://localhost:8000/api/admin/login``

    params:
    
    ``email``
    <br>
    ``password``

    copy token and add on Bearer when necessary

    <br>

11. admin->logout

    method: POST

    ``http://localhost:8000/api/admin/logout``

    on header pass Bearer token

    <br>

12. admin->blogs->list

    method: GET

    ``http://localhost:8000/api/admin/blogs``

    on header pass Bearer token

    <br>

13. admin->categories->list

    method: GET

    ``http://localhost:8000/api/admin/categories``

    on header pass Bearer token

    <br>

14. admin->categories->add

    method: POST

    ``http://localhost:8000/api/admin/categories/add``

    params:

    ``name``
    <br>
    ``status``

    name->string, status->integer(1, 0)->(active, inactive)

    note: both params are mandatory & on header pass Bearer token

    <br>

15. admin->categories->delete

    method: GET

    ``http://localhost:8000/api/admin/categories/delete/{id}``

    pass id url

    on header pass Bearer token

    <br>

16. admin->tags->list

    method: GET

    ``http://localhost:8000/api/admin/tags``

    on header pass Bearer token

    <br>

17. admin->tags->add

    method: POST

    ``http://localhost:8000/api/admin/tags/add``

    params:

    ``name``
    <br>
    ``status``

    name->string, status->integer(1, 0)->(active, inactive)

    note: both params are mandatory & on header pass Bearer token

    <br>

18. admin->tags->delete

    method: GET

    ``http://localhost:8000/api/admin/tags/delete/{id}``

    pass id url

    on header pass Bearer token

    <br>

19. admin->dashboard (to view customers list)

    method: GET

    ``http://localhost:8000/api/admin/dashboard``

    on header pass Bearer token

    note: it will return the users list from users table
