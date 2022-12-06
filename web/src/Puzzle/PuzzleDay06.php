<?php

namespace Puzzle;

use Entity\PuzzleBase;


class PuzzleDay06 extends PuzzleBase {

  private array $data;

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 6;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $this->parseData();

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $this->parseData(14);
  }

  /**
   * Check if the current set has only different characters.
   * @param int $markerSize
   * @return bool
   */
  private function isMarker(int $markerSize): bool {
    $set = array_slice($this->data, 0, $markerSize);
    array_shift($this->data);
    $set = array_flip($set);

    if (count($set) != $markerSize) {
      return FALSE;
    }

    return TRUE;
  }

  private function parseData(int $markerSize = 4):void {
    $this->data = str_split($this->input[0]);
    for($i = 0; count($this->data); $i++) {
      if ($this->isMarker($markerSize)) {
        $this->render('First marker ends at: ' . $i + $markerSize);
        break;
      }
    }
  }
}