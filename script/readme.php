<?php
require_once '../vendor/autoload.php';

/**
 * Example 1
 */
use Logic\Facts;
use Logic\Rule;
use Logic\RuleRunner;
use Logic\Variable;

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


/**
 * Example 2
 */

/* Who does alice like? */
var_dump($likes('alice', '_Who')->toArray());



/**
 * Example 3
 */

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


/**
 * Example 4
 */
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