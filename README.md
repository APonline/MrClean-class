# MrClean-class
This is a class for PHP house keeping and cleaning of regularly needed functions

------

# Methods

### badLanguageFix()

Allows the user to change the censor symbol used.

Param | Description
--- | --- 
$char | A signular symbol declared.

This doesn't return any value it just exits the function after setting a variable.


### badLanguage()

Checks content for specified bad language to censor or to send back for correction.

Param | Description | Value
--- | --- | ---
$content | A string or large text. | *text*
$cta | determines if censor or return strictly not good text to be fixed. | null/censor/judge

This returns an Array, if $cta is set to 'judge' then an extra param 'judgement' = *Pass* OR *Fail* will be included.

##### example var dump
array (
  'fixedCount' => 4,
  'wordsFound' => 
  array (
    0 => 
    array (
      'word' => 'Fucking',
      'count' => 1,
    ),
    1 => 
    array (
      'word' => 'ass',
      'count' => 1,
    ),
    2 => 
    array (
      'word' => 'bitches',
      'count' => 1,
    ),
    3 => 
    array (
      'word' => 'shit',
      'count' => 1,
    ),
  ),
  'original' => 'This a good Fucking test. As I sit on my ass typing away, I roll with the hottest bitches and I simply don\'t give a shit.',
  'edited' => 'This a good F###### test. As I sit on my a## typing away, I roll with the hottest b###### and I simply don\'t give a s#### ',
  'judgement' => 'Fail',
)


### isRequired()

Checks if required fields are:
1. Actually set as a field.
2. Value isn't just blank spaces.
3. Value isn't just blank.

Param | Description
--- | --- 
$args | Array of data submitted by the form.
$req | Array of required fields.

This returns a Boolean, False will equal a fail.


### makeSEOUrl()

Turns string into seo friendly slug.

Param | Description
--- | --- 
$string | String to be altered.

This returns a String value.


### undoSEOURL()

Turns string into display text from a friendly slug.

Param | Description
--- | --- 
$string | String to be altered.

This returns a String value.
