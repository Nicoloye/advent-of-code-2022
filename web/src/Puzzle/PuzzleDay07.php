<?php

declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function array_flip;
use function array_shift;
use function array_slice;
use function count;
use function str_split;


class PuzzleDay07 extends PuzzleBase {

  private array $directories = [];
  private array $sizes = [];

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 7;

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

  }

  /**
   * Check if we go in a folder and update the current directories list.
   * @param string $value
   * @return void
   */
  private function setDirectories(string $value):void {
    preg_match_all('/\$ cd (.+)/', $value, $matches, PREG_SET_ORDER, 0);
    if (count($matches)) {
      if ('..' === $matches[0][1]){
        array_pop($this->directories);
      }
      else {
        $this->directories[] = $matches[0][1];
      }
    }
  }

  /**
   * Increment each folder size of the tree with the files it contains.
   * @param string $value
   * @return void
   */
  private function setSizes(string $value):void {
    preg_match_all('/([0-9]+) (.+)/', $value, $matches, PREG_SET_ORDER, 0);
    if (count($matches)) {
      $path = '';
      foreach($this->directories as $directory) {
        $path .= $directory . '/';
        $size = (int) $matches[0][1];
        array_key_exists($path, $this->sizes) ? $this->sizes[$path] += $size : $this->sizes[$path] = $size;
      }
    }
  }

  /**
   * Calculate the sum of all the folder with a size of at most maxFolderSize.
   * @param int $maxFolderSize
   * @return int
   */
  private function getFolderAtMost(int $maxFolderSize):int {
    $total = 0;
    foreach($this->sizes as $size) {
      if ($size <= $maxFolderSize) {
        $total += $size;
      }
    }
    return $total;
  }

  /**
   * Parse the data and calculate the folder sizes.
   * @param int $maxFolderSize
   * @return void
   */
  private function parseData(int $maxFolderSize = 100000):void {
    foreach($this->input as $value) {

      $this->setDirectories($value);
      $this->setSizes($value);

    }
    $total = $this->getFolderAtMost($maxFolderSize);
    $this->render('Total folder size with ' . $maxFolderSize . ' as folder limit: ' . $total . '<br/>');
  }
}