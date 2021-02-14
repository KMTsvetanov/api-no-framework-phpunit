create table news
(
    id    int auto_increment
        primary key,
    title varchar(40)                         not null,
    date  timestamp default CURRENT_TIMESTAMP not null,
    text  longtext                            null
);

INSERT INTO news (id, title, date, text) VALUES (1, '1234567890123456789012345678901234567893', '2021-02-14 15:19:06', 'Кики');
INSERT INTO news (id, title, date, text) VALUES (2, 'title1 04:45:08pm', '2021-02-14 16:45:08', 'text1');