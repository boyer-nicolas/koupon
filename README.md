# PHP INFRASTRUCTURE BY NIWEE

## Requirements

### Folder structure

⚠️ An `app` folder is required and it <b>must be a Git repository</b> ! ⚠️

```
app/
├── sql/
├── index.php
```

### Getting started example
The following commands will clone this repository, create a new `app` folder and install the PHP infrastructure.
Environment file generation is included

```bash
git clone git@gitlab.com:niwee-productions/infrastructures/php.git <projectname>
cd <projectname>
git clone <myproject> app/
./cli.sh gen:env
docker compose up
```

### Commands

##### With the niwee package, you can use the following command instead of `php.sh`

```bash
lnc
```

#### SQL Dump

```bash
./php.sh db dump
```

#### SQL Import

```bash
./php.sh db import
```
# KOUPON
