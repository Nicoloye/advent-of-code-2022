<?php

declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function array_map;
use function explode;

class PuzzleDay04 extends PuzzleBase {

  private array $pairs;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 4;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $this->getPairs();

    $pairs = 0;
    foreach ($this->pairs as $value) {

      if ($this->isContainedIn($value[0], $value[1]) || $this->isContainedIn($value[1], $value[0])) {
        $pairs++;
      }
    }
    $this->render('Overlapping pairs: ' . $pairs . '<br/>');

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $pairs = 0;
    foreach ($this->pairs as $value) {

      if ($this->overlaps($value[0], $value[1]) || $this->overlaps($value[1], $value[0])) {
        $pairs++;
      }
    }
    $this->render('Overlapping pairs: ' . $pairs . '<br/>');
  }

    /**
   * Preprocess the input to separate all elements.
   * @return void
   */
  private function getPairs(): void {
    $this->pairs = [];
    foreach ($this->input as $value) {
      list($elf1, $elf2) = explode(',', $value);
      $this->pairs[] = array_map(fn($value): array => explode('-', $value), [$elf1, $elf2]);
    }
  }

  /**
   * Check if a range is contained in another.
   * @param array $range1
   * @param array $range2
   * @return bool
   */
  private function isContainedIn(array $range1, array $range2): bool {
    if ($range1[0] >= $range2[0] && $range1[1] <= $range2[1]) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if a range overlaps another.
   * @param array $range1
   * @param array $range2
   * @return bool
   */
  private function overlaps(array $range1, array $range2): bool {
    if ($range1[0] <= $range2[1] && $range1[1] >= $range2[0]) {
      return TRUE;
    }
    return FALSE;
  }

}