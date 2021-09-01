# SANTA INC

### Required

symfony cli ([doc](https://symfony.com/download))

npm `sudo apt install nodejs`

### Run

Start local server

`symfony server:start`

Compile asset

`npm run dev`

Load fixtures

`php bin/console d:f:l`

### API call

```
curl --location --request POST '127.0.0.1:8000/api/file' \
--header 'api_key: e86d6020-b882-3fdc-bf58-ff32a137fe92' \
--header 'Cookie: PHPSESSID=uh5okkhdqqs7i4vah8fv1782g6' \
--form 'file=@"/home/exemple/3491fc41d4e78ff575c5d50dd974366b-83019c95c8c53668db9f480d6cfb9b520f6cf3e8/exemple_usine_A"' \
--form 'factory="1"'
```
