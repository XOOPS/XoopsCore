SchemaTool - a XOOPS developer tool to create schema files

Introduction
============
Schema Tool is a tool to create portable schema definitions for a 
module directly from the database.

With the implementation of the Doctrine Database Abstraction Layer in 
XOOPS 2.6.0, all database interactions in XOOPS moved to a new level 
of isolation from the underlying database engine. One consequence of 
that abstraction is that the traditional SQL files used to create 
tables are now a limitation. While queries written in SQL are largely 
portable if the database adheres to standards, DDL (data definition 
language) used to create tables and indexes varies by vendor and even 
product. To solve this limitation, 2.6.0 implemented schema management 
through Doctrine. Schema Tool can help you create portable schema
definitions.

Installation
============

Notes
=====

Credits
=======
