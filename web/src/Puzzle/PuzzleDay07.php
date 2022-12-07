<?php

declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function array_key_exists;
use function array_pop;
use function count;
use function preg_match_all;
use function sort;

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

    $unusedSpace = 70000000 - $this->sizes['//'];
    $requiredSpace = 30000000 - $unusedSpace;
    $folders = $this->getFoldersWithLimit($requiredSpace, FALSE);
    sort($folders);
    $this->render('Total folder size with ' . $requiredSpace . ' as folder limit to increase ' . $unusedSpace .' of unused space: ' . $folders[0] . '<br/>');
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
   * Retrieve all folders with a size of at most maxFolderSize.
   * @param int $maxFolderSize
   * @return array
   */
  private function getFoldersWithLimit(int $maxFolderSize, bool $foldersUnderLimit = TRUE):array {
    $folders = [];
    foreach($this->sizes as $size) {
      if ($foldersUnderLimit) {
        if ($size <= $maxFolderSize) {
          $folders[] = $size;
        }
      }
      else {
        if ($size >= $maxFolderSize) {
          $folders[] = $size;
        }
      }
    }
    return $folders;
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
    $total = array_sum($this->getFoldersWithLimit($maxFolderSize));
    $this->render('Total folder size with ' . $maxFolderSize . ' as folder limit: ' . $total . '<br/>');
  }
}