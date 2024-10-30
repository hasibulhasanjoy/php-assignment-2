CREATE database php_lab;

use php_lab;

create table
    books (
        id int primary key auto_increment,
        title varchar(30),
        author varchar(30),
        available boolean,
        page int,
        isbn VARCHAR(20)
    );

INSERT INTO
    books (title, author, available, page, isbn)
VALUES
    (
        'To Kill A Mockingbird',
        'Harper Lee',
        true,
        336,
        '9780061120084'
    ),
    (
        '1984',
        'George Orwell',
        true,
        267,
        '9780547249643'
    ),
    (
        'One Hundred Years Of Solitude',
        'Gabriel Garcia Marquez',
        false,
        457,
        '9785267006323'
    );