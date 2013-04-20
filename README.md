# Superclass PHP Framework #

Superclass is a modern and ultra-extensible PHP framework for database-centric applications. It makes use of database metadata to aid developer in creating common database forms and in validating data. Developer can customize standard functionality by subclassing framework classes. 

Currently it supports only PostgreSQL, but support for other databases is only matter of writing drivers. The only requirement is that database must expose it's internals through [information_schema][].

Superclass was inspired by [Oracle Forms][Forms] and [Oracle Application Express][APEX] (standard forms, use of metadata), [CodeIgniter][CI] (concise code, extensibility), [Qt][] (subclassing of generated code). It used to be called "APEX for PostgreSQL".

[information_schema]: http://en.wikipedia.org/wiki/Information_schema
[Forms]: http://www.oracle.com/technetwork/developer-tools/forms/index.html
[APEX]: http://www.oracle.com/technetwork/developer-tools/apex/index.html
[CI]: http://ellislab.com/codeigniter
[Qt]: http://qt-project.org/
