#Assembla to github

---

This is a small command line tool that leverages github's api v3 
and their new experimental issue import api's to migrate tickets
from assembla, into github keeping the dates, milestones, departments,
comments, and assignees. 

To use it simply download or clone the repo and first run 
composer install to get the required dependencies. Then run the
script using
````
assembla:github assembla-file.bak username access_token reponame -m djOo_iMbKr44kTacwqjQYw:samuraiseoul,c0_SD-nx4r4PHmacwqEsg8:githubname
````

If you find this no longer works or doesn't work for your needs let me know
and we can try to modify it to get what you need, or simply submit a pull request.

