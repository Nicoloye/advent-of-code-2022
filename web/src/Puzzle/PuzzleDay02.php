<?php

namespace Puzzle;

use Entity\PuzzleBase;

class PuzzleDay02 extends PuzzleBase {

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 2;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $this->play('WithClues');

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    $this->play('ByGuessing');
  }

  private function play($gameType) {
    $score = 0;
    foreach ($this->input as $value) {
      list($elf, $player) = \call_user_func([$this, 'switchToScores' . $gameType], $value);
      $score += $this->solveTurn($elf, $player);
    }
    $this->render('Total score: ' . $score . '<br/>');
  }

  /**
   * Switch characters to their score values based on the elf clues.
   * @param string $value
   * @return array
   */
  private function switchToScoresWithClues($value): array {
    $value = \str_replace(['A', 'X'], 1, $value);
    $value = \str_replace(['B', 'Y'], 2, $value);
    $value = \str_replace(['C', 'Z'], 3, $value);
    return \explode(' ', $value);
  }

  /**
   * Switch characters to their score values by guessing the figure based on the attended outcome.
   * @param string $value
   * @return array
   */
  private function switchToScoresByGuessing($value): array {
    list($elf, $player) = $this->switchToScoresWithClues($value);

    switch ($player) {
      // We intend to perform a draw.
      case 2 :
        return [$elf, $elf];
      // We intend to lose.
      case 1 :
        return [$elf, (($elf + 1) % 3) + 1];
      // We intend to win.
      case 3:
        return [$elf, ($elf % 3) + 1];
    }
  }

  /**
   * Solve this turn and return the score for the player.
   * @param $score
   * @param $elf
   * @param $player
   * @return int
   */
  private function solveTurn($elf, $player): int {
    // In any case we add the current figure score.
    $score = $player;

    // If both numbers are identical it's a draw.
    if ($elf === $player) {
      return $score + 3;
    }

    // If both numbers are odd, the smallest wins (rock vs scissors),
    if ($elf % 2 !== 0 && $player % 2 !== 0) {
      if ($elf > $player) {
        return $score + 6;
      }
    }
    // Otherwise it's the biggest number that wins.
    else {
      if ($player > $elf) {
        return $score + 6;
      }
    }

    return $score;
  }

}