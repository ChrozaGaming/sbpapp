create table absences
(
    absence_id  int unsigned auto_increment
        primary key,
    employee_id int unsigned                                               not null,
    date        date                                                       not null,
    status      enum ('Pending', 'Approved', 'Rejected') default 'Pending' null
);

create index fk_employee
    on absences (employee_id);

