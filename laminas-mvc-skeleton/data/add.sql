alter table todolist add column status varchar default 'new';
INSERT INTO todolist (status) VALUES ('new');
SELECT * FROM todolist;