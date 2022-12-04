<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay03 extends PuzzleBase {

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 3;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $score = 0;
    foreach ($this->input as $value) {
      list($compartment1, $compartment2) = $this->getCompartments($value);
      $similar = $this->getSimilar($compartment1, $compartment2);
      $score += $this->charToCode($similar[0]);
    }

    $this->render('Total score: ' . $score);

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $score = 0;
    for ($i = 0; $i < \count($this->input); $i += 3) {
      $rusack1 = $this->input[$i];
      $rusack2 = $this->input[$i + 1];
      $rusack3 = $this->input[$i + 2];

      $similar = $this->getSimilar($rusack1, $rusack2, $rusack3);
      $score += $this->charToCode($similar[0]);
    }

    $this->render('Total score: ' . $score);
  }

  /**
   * Retrieves the two compartment of the rucksack.
   * @param $rucksack
   * @return array
   */
  private function getCompartments($rucksack): array {
    $size = \strlen($rucksack);
    $compartment1 = \substr($rucksack, 0, $size / 2);
    $compartment2 = \substr($rucksack, $size / 2, $size);
    return [$compartment1, $compartment2];
  }

  /**
   * Convert a character in its priority number.
   * @param string $char
   * @return int
   */
  private function charToCode($char): int {
    $charCode = \ord($char);

    if ($charCode > 96) {
      return $charCode - 96;
    }

    return $charCode - 38;
  }

  /**
   * Retrieve the similar element in the compartments of the rucksack.
   * @param string $compartment1
   * @param string $compartment2
   * @param string $compartment3
   * @return int[]|string[]
   */
  private function getSimilar($compartment1, $compartment2, $compartment3 = ''): array {
    if ('' === $compartment3) {
      $compartment3 = $compartment1;
    }

    $compartment1 = \array_flip(\str_split($compartment1));
    $compartment2 = \array_flip(\str_split($compartment2));
    $compartment3 = \array_flip(\str_split($compartment3));
    $intersection = \array_intersect_key($compartment1, $compartment2, $compartment3);

    return \array_keys($intersection);
  }

}