<?php

namespace Tests\Feature;

use App\Player;
use App\VoteSite;
use App\VoteValidation\ServerVoteValidator;
use App\VoteValidation\Test\FakeVoteValidation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\FakeInvalidVoteValidation;
use Tests\SeededTest;
use Tests\TestCase;

class ServerVoteValidatorTest extends SeededTest
{
    use RefreshDatabase;

    protected $validator;
    protected $siteAlwaysValid;
    protected $siteAlwaysInvalid;
    public function setUp(): void
    {
        parent::setUp();
        $this->siteAlwaysValid = factory(VoteSite::class)->create(['validator'=>FakeVoteValidation::class]);
        $this->siteAlwaysInvalid = factory(VoteSite::class)->create(['validator'=>FakeInvalidVoteValidation::class]);
        $this->siteAlwaysInvalid->addRegisteredServer($this->server,10);
        $this->siteAlwaysValid->addRegisteredServer($this->server,11);
        $this->validator = new ServerVoteValidator($this->server);
    }

    public function testGetVotesOf()
    {
        $this->assertEquals(1, count($this->validator->getVotesOf(factory(Player::class)->create())));
    }
}
