<?php


namespace Tests;


use App\VoteValidation\Test\FakeVoteValidation;

class FakeInvalidVoteValidation extends FakeVoteValidation
{
    public function hasVoted($response): bool
    {
        return false;
    }
}
