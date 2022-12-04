<?php

namespace Puzzle;

use Entity\PuzzleBase;
use function array_key_exists;
use function rsort;

class PuzzleDay01 extends PuzzleBase {

  /**
   * @var array $elf_calories
   *   The elves calories sums, one elf per key.
   */
  private array $elf_calories;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 1;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    // Get each elf calories count and return the highest.
    $this->elf_calories = $this->processInput();
    $this->render('Highest elf calories count: ' . $this->elf_calories[0] . '<br/>');

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    // Get each elf calories count and return the top3 highest.
    $this->render('Elf calories count top3: <br/><ol>');
    $this->render('<li>' . $this->elf_calories[0] . '</li>');
    $this->render('<li>' . $this->elf_calories[1] . '</li>');
    $this->render('<li>' . $this->elf_calories[2] . '</li>');
    $this->render('</ol>');
    $this->render('Total: '.($this->elf_calories[0] + $this->elf_calories[1] + $this->elf_calories[2]). '<br/>');
  }

  /**
   * Collect all elves calories.
   * @return array
   */
  private function processInput(): array {
    $current_elf = 0;
    $elf_calories = [];
    foreach ($this->input as $value) {
      if (!$value) {
        $current_elf++;
        continue;
      }

      array_key_exists($current_elf, $elf_calories) ? $elf_calories[$current_elf] += $value : $elf_calories[$current_elf] = $value;
    }
    rsort($elf_calories);
    return $elf_calories;
  }

}