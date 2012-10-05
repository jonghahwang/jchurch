The History of Version Control

Source Code Control System (SCCS)
    - 1972, closed source, free with Unix
    - keep the original version and then save the changes only
    
Revision Control System (RCS)
    - 1982, open source, cross platform
    - keep the latest version and subtract the the changes from it
    
Concurrent Versions System (CVS)
    - 1986 ~ 1990, open source
    - many users can work together
    - have a remote repository and many user can work on the same file concurrently
    - if you rename the file, it is hard to keep track of the changes
    
Apache Subversion (SVN)
    - 2000, open source
    - keep track of the changes on the directory, not just files.
    - taking the snapshot of the directory, not just the files.
    - can store images, not just text files
    - since it watches whole directory, even if you changes the file name it can track

BitKeeper SCM
    - 2000, closed source, proprietary
    - distributed version control
    - community version was free
    - used for source code of the linux kernel from 2002 ~ 2005
    - controversial to use propeirtary SCM for an open source project
    - April 2005: the community version not free anymore

Git it born
    - April 2005
    - created by Linus Torvalds
    - distributed version control
    - faster than other SCMs (100x)
    
Git is a hit
    - GitHub launched in 2008
    - 2011: over 2 milion repositories


About Distributed Version Control

Distributed version control

    - different users (or teams of users) maintains their own repositories, instead of working from a cenrtal repository
    - changes are stored as "change sets" or "patches"
    - it trackes changes, not versions of documents
    - other SCS track changes from version to version
    - Git just focuses on the changes set
    - changes sets can be exchanged between repositories
    - "merge in change sets" or "apply patches"
    - no single mater repository; just many working copies
    - each with thei own combination of change sets

Imagin changes to a document as sets A B C D E F G
    - Repo 1: A B C D E
    - Repo 2: A B C D
    - Repo 3: A B C E
    - Repo 4: A B E F
    
No need to communicate with a central server
    - faster
    - no network access required
    - no single failure point
    - encourage participation and forking of projects
    - developers can work independently
    - submit change sets for inclusion or rejection
    
Configuration

    - System level : /etc/gitconfig
    - User level : ~/.gitconfig
    - Project level : my_project/.git/config
    
How to create a configuration
    - git config --global
    : git config --global user.name "Jongha Hwang"
    : git config --global user.email "jongha@jongha.com"

How to tell git what text editor you are using
    : git config --global core.editor "mate -wl1" 
    
Using color
    : git config --global color.ui true
    
Auto Completion
    - Download from Github
    : cd ~
    : curl -OL https://github.com/git/git/raw/master/contrib/completion/git-completion.bash
    : mv ~/git-completion.bash ~/.git-completion.bash
    : vi .bashrc
    - and then add following lines in your bash profile
    if [ -f ~/.git-completion.bash ]; then
      source ~/.git-completion.bash
    fi
    
Git help
    - git help <command>
    - while you are viewing the document, use "F" to go forward
    - use "b" backward, "q" for quit
    - or you can use :man git-log
    
Initialization
    - create a directory and move in
    : git init
    
Performing your first commit
    : git add .
    : git commit -m "initial commit"
    
Best practice for writing commit message
    - short single-line summary (less than 50 characters)
    - optionally followed by a blank line and a more complete description
    - keep each line to less than 72 characters
    - write commit messages in present tense
    - bullet points are usually asteriskes or hyphens
    - can add "ticket tracking number" from bugs or support requests
    - can develop shorthand for your organization
        - "[css, js]"
        - "bugfix:"
        - "#38405 - "
    - Be clear and descriptive
        - Bad: "Fix typo"
        - Good: "Add missing > in project section of HTML"
        - Bad: "Update login code"
        - Good: "Change user authentication to use Blowfish"

Good example of commit message
    
    t25098 - Fixes bug in admin logout
    
    When an admin logged out of the admin area, they could not log in the members area because
    their session[:user_id] was still set to the admin ID. This patch fixes the bug by 
    setting[:user_id] to nil when any user logs out of any area.

Viewing Commit Log
    :git log
    :git log - 3                        (last 3 commits)
    :git log --since=2012-06-15
    :git log --until=2012-10-02
    :git log --author=Jongha            (ant part of your name, case-sensitive)
    :git log --grep="Init"
    
    
ARCHITECTURE OF GIT

Two-tree architecture
    - repository 
    - working
    
Three-tree architecture
    - repository 
    - staging index
    - working

Using hash values(SHA-1)
    - When git put change set into repository it generates a checksum for each change set
        - checksum algorithms convert data into a simple number
        - same data always equals same checksum
    - Data integrity is fundamental
        - changing data would change checksum
    - Git uses SHA-1 hash algorithm to create checksums
        - 40-character hexadecimal string (0-9,a-f)
        
HEAD Point
    - pointer to "tip" of current branch in repository
    - last state of repository, what was last checked out
    - points to parent of next commit
        - where writing commits takes place
    - where does HEAD points now?
        : cat refs/heads/master
        - then you will see hash value
        - using this value check git log and you know where HEAD points to
    

MAKING CHANGES TO FILES

Adding Files
    : git status
    # On branch master
    nothing to commit (working directory clean)
    (this means that working, staging and repository are the same)
    
    - create new files and do git status
    :git status
    # On branch master
    # Untracked files:
    #   (use "git add <file>..." to include in what will be committed)
    #
    #	second_file.txt
    #	third_file.txt
    nothing added to commit but untracked files present (use "git add" to track)
    
    - now just add second file into staging directory
    :git add second_file.txt
    :git status
    # On branch master
    # Changes to be committed:
    #   (use "git reset HEAD <file>..." to unstage)
    #
    #	new file:   second_file.txt
    #
    # Untracked files:
    #   (use "git add <file>..." to include in what will be committed)
    #
    #	third_file.txt
    
    - now commit
    : git commit -m "Add second file to project"
    [master 66f8ed5] Add second file to project
     1 file changed, 1 insertion(+)
     create mode 100644 second_file.txt
    
Editing
    - edit process is the same as adding
    - move files to staging and commit
    
Viewing changes with diff
    :git diff               (show changes in working directory)
    :git diff --staged     (show changes in staging directory)
    
Delete
    - after you delete a file
    :git rm filename.txt
    :git commit -m "Delete filename.txt"

Move and Rename
    - if you rename a file and then run git status, you will see untracked file message
    - so you have to
    :git mv first_file.txt primary.txt
    

WORKING WITH PROJECT
    - go to the parent directory of your project and then initialize it (explorer_california)
    :git init
    :git add .
    :git commit -m "Initial commit"
    
    - let's make some changes (4315 -> 4314) and then run diff
    :git diff contact.html
    - the result might be too long or too wide, because the result is run by "pager"
      if you resize the terminal window very small you will see ":" on the left bottom
      This is left pager and prompt you to enter command. you can enter "F" for forward
      or "B" for backward.
    - if you want to toggle long lines, minus sign (-) + shift + s 
      then it will prompt "Fold long lines (press ENTER)" and then hit return
    
    :git diff --color-words contact.html        (this will put the change side by side)

    - if you want to add all modified files
    :git add .
    :git commit -am "message"  (this is a shortcust that add and commit together. works only for edit)

Editing filename and links to that
    - first find the file and rename it
    :git mv tours/tour_detail_backpack.html tours/tour_detail_backpack_cal.html
    
    - now you need to find all the links to that file


UNDOING CHANGES

Undoing working directory changes
    - use git checkout, "--" means stay on the current branch
    :git checkout -- index.html
    
Unstaging files
    - git reset HEAD resources.html

Amending commits
    - we can change only the last commit in the repository
    - why? if you change old commit, there will be new SHA value will be generated
      since all children commits are dependant on this value all children commit needs to be changed.
      This will cause integrity issue. So it is difficult to change old commit.
    - after making changes on the last file if you add the file into the respository
    - use "--amend"
    :git commit --amend -m "Rearranged items to bring on a trip"
    - hash value will change

Retrieving old version
    - grep some part of the SHA value of the commit you want to revert
    
    :git checkout 613f72fd3d6ee5 -- resources.html
    
    - here "--" means current branch
    - this will bring resouces.html with the version in commit 613f72fd3d6ee5
    - now the file is in staging directory
    - if you commit now it will overwrite what's in the repository
    - when you commit on existing it is good practice to use the hash value

Reverting a commit
    - revert command will flip around any changes that made previously


Using reset to undo commits
    - soft: does not change staging index or working directory
    - mixed(default): changes staging index to match repository
        but does not change working directory
    - hard: changes staging index and working directory to match repository
    
    *********** Example *****************
    :git log
    
        commit 105a3f4de848bda4ff5fd248ed9c09c083b1642a
        Author: Jongha Hwang <jongha@jongha.com>
        Date:   Wed Oct 3 10:10:23 2012 -0700

            Rearranged items to bring on a trip2

        commit 613f72fd3d6ee575487ba51c2745793b80e7bcc4
        Author: Jongha Hwang <jongha@jongha.com>
        Date:   Tue Oct 2 12:33:52 2012 -0700

            Contact page has contraction changes

        commit 271850561f59d854b9c5fc46eeb3ee65f1eb84df
        Author: Jongha Hwang <jongha@jongha.com>
        Date:   Tue Oct 2 12:32:36 2012 -0700

            Rename backpack_cal for clarity

        commit 49e37734ad984cd216b80cd123b4aa41b67613cf
        Author: Jongha Hwang <jongha@jongha.com>
        Date:   Tue Oct 2 12:21:47 2012 -0700

            Changed 24 hour support number to 4314

        commit 3412805d057a3ec2a4ff8bfc094da22db5ac5e51
        Author: Jongha Hwang <jongha@jongha.com>
        Date:   Tue Oct 2 11:58:07 2012 -0700

            Initial commit
    ******************************************
            
    Soft reset
    :git reset --soft 613f72fd3d6ee5754
    - this will reset to the commit of 613f72fd3d6ee5754 only on repository
    - basically set the HEAD pointer to where you want using SHA value
    - this is the saftest reset because it won't changes anything on working and staging area

    Mixed reset
    :git reset --mixed 613f72fd3d6ee5754
    - this will reset to the commit of 613f72fd3d6ee5754 on repository and also staging area
    
    Hard reset
    :git reset --hard 613f72fd3d6ee5754
    - when things are really screwed on your working directory you can use this

Removing untracked files
    :git clean -n       (test runs with messages)
    :git clean -f       (will not clean the files in staging area, but only working directory)
    
    
IGNORING FILES

Using .gitignore
    - set rules to ignore some files such as log file or temporary files
    - use ver basic regular expressions
    - negate expressions with !
        - *.php         (ignore all files with .php)
        - !index.php    (not index file)
    - ignore all files in a directory with trailing slash
        - assets/videos/
    - comment lines begin witg #, blank lines are skipped
    
Understanding what to ignore
    - compliled source code
    - packages and compressed files (gz or zip)
    - logs and databases (files changes often)
    - operating system generated files
    - user-uploaded assets (images, PDFs, videos)
    
    https://help.github.com/articles/ignoring-files
    https://github.com/github/gitignore

Ignoring files globally
    - ignore files in all repositories
    - settings not tracked in repository
    - user-specific instead of repository-specific
    - create a file, .gitignore_global under your user directory
    :vi .gitignore_global
    :git config --global core.excludesfile /Users/jongha/.gitignore_global
    :cat /Users/jongha/.gitconfig
    [user]
    	name = Jongha Hwang
    	email = jongha@jongha.com
    [core]
    	editor = mate -wl1
    	excludesfile = /Users/jongha/.gitignore_global
    [color]
    	ui = true
    
    Example: my own global ignore file
    **********************************************
    
    #--------------------#
    # OS Generated files #
    #--------------------#
    .DS_Store
    .Trashes
    .Spotlight-V100
    .Thumbs.db

    #------------------------#
    # Textmate project files #
    #------------------------#
    *.tmproj
    *.tmproject
    tmtags

    #---------------------------#
    # Codeigniter related files #
    #---------------------------#
    */config/development
    */logs/log-*.php
    */logs/!index.html
    */cache/*
    */cache/!index.html
    
    **************************************************

Ignoring tracked files
    - ignoring rules works only new file
    - if you want to ignore you need to tell git stop tracking it
    :git rm --cached tempfile_new.txt

    - here cached means staging index (area)
    - this will remove it from staging area only
    - the file will be in working directory and repository

Tracking empty directory
    - git does not track empty directory
    - just put a tiny file in that directory
    :touch .gitkeep         (filename does not matter, just  empty file)


NAVIGATING COMMIT TREE

Referencing commit
    - tree-ish: ish means like something
    - use full SHA-1 hash to reference commit
    - use short SHA-1 hash ( at least 4 characters )
    - use HEAD pointer
    - use branch reference, tag reference
    - ancestry
        - parent commit
            - HEAD^, acf87654^, master^
            - HEAD~1, HEAD~
        - grandparent commit
            - HEAD^^
            - HEAD~2

Exploring tree listing
    :git ls-tree HEAD
    :git ls-tree master
    :git help ls-tree       (you need to pass tree-ish)
    :git ls-tree HEAD^      (go back to previous)
    
    - blog: files
    - tree: directory

Getting more from log
    :git log --oneline
    :git log --oneline -3               (only 3 commits)
    :git log --since="2012-10-01"
    :git log --until="2012-10-01"
    :git log --since="2 weeks ago" --until="3 days ago"
    :git log --author="Jongha"
    :git log --grep="temp"
    :git log 203c265..0be3a73           (pass SHA as range)
    :git log 3412805.. pdf              (since the initial commit, what happened to pdf directory)
    :git log -p index.html              (show details)
    :git log --stat --summary
    :git log --format=oneline
    :git log --format=short
    :git log --format=medium
    :git log --format=full
    :git log --format=fuller
    :git log --format=email
    :git log --format=raw
    :git log --graph
    :git log --oneline --graph --all --decorate

View commit
    :git show 0be3a73
    :git show --format=oneline HEAD

Compare commit
    :git diff be83b10               (compare current working area and the point where be83b10 commit made)
    :git diff 34128ds tours.html    (compare the changes made only on tours.html file)
    :git diff be83b10..34128ds      (compare two snpshots)
    :git diff be83b10..34128ds tours.html
    :git diff --stat --summary be83b10..HEAD
    :git diff --stat --ignore-space-change be83b10..HEAD
        this is the same as :git diff -b be83b10..HEAD
    :git diff --stat --ignore-all-space be83b10..HEAD
        this is the same as :git diff -w be83b10..HEAD


BRANCH
    
Overview
    - branches are cheap, easy to deal with
    - try new ideas, just create a new branch
    - branch allows isolate features or sections of work
    - one working directory, fast context switching

Viewing and creating
    :git branch new_feature
    :ls -al .git/refs/heads         (then you will see a new file new_feature)
                                    (inside this file you will see head SHA)
    :git checkout new_feature
    
    - after changing branch make some changes and then commit
    - this change will show up on the new_feature branch, but not master, try :git log --oneline
    - if you switch back to master branch :git checkout master
    - the change you made in new_feature branch is gone (this is what fast context switching means)

Creating and switching branch at the same time
    - git checkout -b shorten_title
    
Switching branches with uncommitted change
    - after you make some change on working directory and trying to switch to master branch, get error
        error: Your local changes to the following files would be overwritten by checkout:
    	    tours.html
                Please, commit your changes or stash them before you can switch branches.
    - this means if you switching branches while some changes are not committed, you might lose the changes
    - here, "stash" means to keep uncommited change in temporary place.
    - but if there is a new file in working directory, it won't stop switching because there is no risk of losing changes
    
Comparing branches
    :git diff master..new_feature
    :git diff --color-words master..new_feature
    :git diff master..new_feature^
    :git branch --merged
    
    - this shows that all the branches that has the commits in other branches
    - after switching to other branch and try this. master branch does not have new_feature unless it is merged

Rename branches
    :git branch --move new_feature change_character     (move means rename here)
    :git branch -m new_feature change_character         (shortcut)

Delete branches
    :git branch --delete branch_delete 
    :git branch -d branch_delete 
    
    - if you are trying to delete the branch you are on, you will get an error. has to switch to other branch
    - if you are trying to delete the branch that you have some commit, but not merged yet, you will get an error
    
        error: The branch 'branch_delete' is not fully merged.
        If you are sure you want to delete it, run 'git branch -D branch_delete'.
    
Configuring the command prompt to show the branch
    - in Unix prompt is stored in PS1 (Prompt Sign 1)
    :export PS1='\W$(__git_ps1 "(%s)") > '
    - __git_ps1
    - if you don't have this function defined, define in your profile
        open .bashrc and then add following
    
        ------------------------------------------
        
        # defind git branch promt
        __git_ps1 ()
        {
            local b="$(git symbolic-ref HEAD 2>/dev/null)";
            if [ -n "$b" ]; then
                printf " (%s)" "${b##refs/heads/}";
            fi
        }

        export PS1='\W$(__git_ps1 "($s)") :'
        
        ------------------------------------------

MERGING BRANCHES

Merging code
    - first you check out the branch that you are merging into, and then
    :git merge character_chage
    
Using fast-forward merge and true merging
    - fast-forward merge: after merging a new branch if there is no commit on master branch yet
      the merge is fast-forward merge, which means that just move HEAD pointer to new branch.
      At this point if you check the log, the commit SHA on new branch and master branch are the same
      because git just move(fast-forward) the pointer to the new branch
    
    :git merge --no-ff branch   (do merge without fast-forward)
    :git merge --ff branch      (do merge only if you can do fast-forward merge)
    
    - true merge: if there is another commit on master you can't do fast-forward merge. you have to do
      true merge.
    
    :git merge shorten_title 
      Merge made by the 'recursive' strategy.
       tours.html | 2 +-
       1 file changed, 1 insertion(+), 1 deletion(-)
    
    - this means that there is a new commit made on master branch
        da5188c Merge branch 'shorten_title'
        422b62e Edit contact.html title
        
Merging conflict
    - when the changes on the same file in different branch are different git can merge them automatically
      but if the changes are on the same line, then it creates conflict and git will generate message.
      and display "<<<<<<< HEAD" on the the file
    - if you check the status you will see 
    
        # On branch master
        # You have unmerged paths.
        #   (fix conflicts and run "git commit")
        #
        # Unmerged paths:
        #   (use "git add <file>..." to mark resolution)
        
    
Resolve merge conflict
    - abort merge
    - resolve the conflicts manually
    - use a merge tool
    
    :git merge --abort
    :git mergetool
    :git log --graph --oneline --all --decorate
    
    - beware stray edits to whitespace, spaces, tabs, line returns
    - merge often
    - track changes to master (try to sync with master as often as possible)
    

STASHING CHANGES

Saving changes in statsh
    :git stash save "changed mission page title"
    :git stash list
    :git stash show stash@{0}
    :git stash show -p stash@{0}
    :git stash pop stash@{0}                  (remove from stash storage)
    :git stash apply stash@{0}                (keep the copy in stash storage)
    :git stash drop stash@{0}
    :git stash clear                          (delete all stashes)
        
    
REMOTES

Using local and remote repositories
    - origin/master: this is a kind of branch that is sync with remote server
        it is just a pointer, not replication of the objects
    
    :git remote
    :git remote add <alias> <url>
    :git remote add origin https://github.com/jonghahwang/explore_california.git
    :git remote -v

Create remote branch
    :git push -u origin master      (push up the master branch)
    
Cloning remote repository
    :git clone https://github.com/jonghahwang/explore_california.git lynda_version









Setting alias for command git command
    - git config --global alias.st status       (this will be in .gitconfig file)
    - git config --global alias.co checkout
    - git config --global alias.ci commit
    - git config --global alias.br branch
    - git config --global alias.dfs "diff --staged"
    - git config --global alias.logg "log --graph --decorate --oneline --abbrev-commit --all"

Graphical user interface
    - GitX          http://gitx.org
    - GitHub        http://mac.github.com
    - Source Tree   http://sourcetreeapp.com
    - Tower         http://www.git-tower.com        ****
    - SmartGit      http://syntevo.com/smartgit
    - Gitbox        http://gitboxapp.com
    
    
Git hosting company
    - https://github.com/
    - https://bitbucket.org/
    - http://gitorious.com/

Git Self-Hosting
    - Gitolit






