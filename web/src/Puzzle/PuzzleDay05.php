<?php

namespace Puzzle;

use Entity\PuzzleBase;
use function array_filter;
use function array_map;
use function array_merge;
use function array_pop;
use function array_reverse;
use function array_slice;
use function count;
use function explode;
use function preg_replace;
use function str_replace;
use function str_split;

class PuzzleDay05 extends PuzzleBase {

  private array $stacks;
  private array $startingStack;
  private array $craneMoves;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 5;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $this->initializeData();

    $this->moveCrates();

    $this->render('Last cranes on each pile: ' . $this->getLastCranes());

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $this->stacks = $this->startingStack;

    $this->moveCrates(TRUE);

    $this->render('Last cranes on each pile: ' . $this->getLastCranes());
  }

  /**
   * Initialize the data with two separate
   * @return void
   */
  private function initializeData(): void {
    $craneMoves = FALSE;
    foreach($this->input as $value) {
      // Switch the variable to initialize.
      if ('' === $value) {
        $craneMoves = TRUE;
        $this->cleanAndtransposeStacks();
        continue;
      }

      if (!$craneMoves) {
        $this->startingStack[] = $this->getStartingStacks($value);
        continue;
      }

      $this->craneMoves[] = $this->getCraneMoves($value);
    }
  }

  /**
   * Retrieve the starting stacks as an array.
   * @param string $value
   * @return array
   */
  private function getStartingStacks(string $value):array {
    // Keep only even characters.
    $value = preg_replace('/(.)(.)/', '$2', $value);

    // Replace empty crates with a placeholder character #, clean useless spaces and brackets.
    $value = str_replace('  ', '#', $value);
    $value = str_replace(['[', ']', ' '] , '', $value);

    // Return an exploded array.
    return str_split($value);
  }

  /**
   * Clean & transpose the array for better management.
   * @return void
   */
  private function cleanAndtransposeStacks():void {
    // Remove the stack numbers row.
    array_pop($this->startingStack);

    // Transpose the array.
    $this->startingStack = array_map(null, ...$this->startingStack);
    // Reverse the crane order to have the top crane as the last item.
    $this->startingStack = array_map(fn($value): array => array_reverse($value), $this->startingStack);
    // Remove empty crane placeholder #.
    $this->startingStack = array_map(fn($value): array => array_filter($value, fn( $value ): bool => ($value && $value != '#') ), $this->startingStack);

    // Create a separate stack to apply crane moves on it and preserve the initial state.
    $this->stacks = $this->startingStack;
  }

  /**
   * Retrieve the crane moves.
   * @param string $value
   * @return array
   */
  private function getCraneMoves(string $value):array {
    $value = preg_replace('/[a-zA-Z]+ ([0-9])/', '$1', $value);
    return explode(' ', $value);
  }

  /**
   * Return a string with the last cranes of each pile.
   * @return string
   */
  private function getLastCranes():string {
    $lastCranes = '';
    foreach ($this->stacks as $stack) {
      $lastCranes .= array_pop($stack);
    }
    return $lastCranes;
  }

  /**
   * Perform the
   * @param bool $craneModel9001
   * @return void
   */
  private function moveCrates(bool $craneModel9001 = FALSE):void {
    foreach($this->craneMoves as $move) {
      $cranes = $move[0];
      $source = $move[1] - 1;
      $destination = $move[2] - 1;
      $sourceSize = count($this->stacks[$source]);

      // Put the cranes on the destination stack.
      $cranesPile = array_slice($this->stacks[$source], $sourceSize - $cranes, $cranes);
      if (!$craneModel9001) {
        $cranesPile = array_reverse($cranesPile);
      }
      $this->stacks[$destination] = array_merge($this->stacks[$destination], $cranesPile);

      // Remove the cranes from the source stack.
      $this->stacks[$source] = array_slice($this->stacks[$source], 0, $sourceSize - $cranes);
    }
  }

}