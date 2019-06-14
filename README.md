Logic
=====

This PHP library was inspired by PROLOG which is a Logic Programming Language. It implements the core concept of Logic 
Programming like unification and clause resolution.

People who are accustomed with PROLOG will find the main features of this language.


Install
-------

`composer require kirouane/logic`

Examples
--------

The examples below are based on this excellent [article](https://bernardopires.com/2013/10/try-logic-programming-a-gentle-introduction-to-prolog/) about PROLOG.

#### Example 1 : Queries

```php
<?php
use Logic\Facts;
$likes = new Facts();

// fact declaration
$likes->is('alice', 'bob');
$likes->is('bob','carol');
$likes->is('james', 'mary');
$likes->is('mary', 'james');

/* Let's do some queries*/


var_dump($likes('alice', 'bob')->found());
//bool(true) | We found a match in our facts !

var_dump($likes('bob', 'alice')->found());
//bool(false) | Bob doesn't like alice according to our facts

var_dump($likes('mary', 'john')->found());
//bool(false) | Bob doesn't like alice according to our facts
``` 

#### Example 2 : Variables

Now, we want to know who Alive likes. Whe achieve this by   

using variables. A variable must start with underscore **_** character. Let's see un example.

```php
/* Who does alice like? */
var_dump($likes('alice', '_Who')->toArray());

/*
array(1) {
  [0] =>
  array(1) {
    '_Who' =>
    string(3) "bob"
  }
}
*/
``` 


#### Example 3 : Rules

Let’s write a rule called "love compatible" using the facts we already defined above.

```php
$loveCompatible = new Rule(function($x, $y) use($likes) {
    /** @var RuleRunner $this */
    return $this->andLogic(
        $likes($x, $y),
        $likes($y, $x)
    );
});


/* Now let’s make some queries */

//Is james compatible with someone?
var_dump($loveCompatible('james', '_Who')->toArray());

/*
array(1) {
  [0] =>
  array(1) {
    '_Who' =>
    string(4) "mary"
  }
}
 */

// find all love pairs with the known facts
var_dump($loveCompatible('_X', '_Y')->toArray());

/*
array(2) {
  [0] =>
  array(2) {
    '_X' =>
    string(5) "james"
    '_Y' =>
    string(4) "mary"
  }
  [1] =>
  array(2) {
    '_X' =>
    string(4) "mary"
    '_Y' =>
    string(5) "james"
  }
}
 */
```

#### Example 4 : Rules

Let’s take a look at a more complex example.

```php
$mother = new Facts();
$father = new Facts();

$mother->is('alice', 'lea');
$mother->is('john', 'julia');
$mother->is('lea', 'alberta');
$father->is('james', 'alfred');
$father->is('lea', 'john');


$parent = new Rule(function($x, $y) use($mother, $father) {
    return $this->orLogic(
        $mother($x, $y),
        $father($x, $y)
    );
});

$grandParent = new Rule(function($x, $y) use($parent) {
    $z = new Variable();

    return $this->andLogic(
        $parent($x, $z),
        $parent($z, $y)
    );
});


// Who are alice's grandparents
var_dump($grandParent('alice', '_Who')->toArray());

/*
array(2) {
  [0] =>
  array(1) {
    '_Who' =>
    string(7) "alberta"
  }
  [1] =>
  array(1) {
    '_Who' =>
    string(4) "john"
  }
}
*/
```


More features
-------------

* Recursion
* Filtering
* Ability to use native php functions and operators


Upcoming features
-----------------

* Arrays
* Objects




