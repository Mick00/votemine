<?php

use App\VoteSite;
use App\VoteValidation\Test\FakeVoteValidation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class VotingSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO: https://www.liste-serveur.fr/
        $this->addVotingSite(
            'https://www.serveursminecraft.org',
            'ServeursMinecraft.org',
            'logos/serveursminecraft.org',
            'https://www.serveursminecraft.org/serveur/{id}/',
            24,
            \App\VoteValidation\org\serveursminecraft\PlayerVoteValidation::class);
        $this->addVotingSite(
            'https://www.serveurs-minecraft.org',
            'Serveurs-Minecraft.org',
            'logos/serveurs-minecraft.org',
            'https://www.serveurs-minecraft.org/vote.php?id={id}',
            24,
            \App\VoteValidation\org\serveurs_minecraft\PlayerVoteValidation::class
        );
        $this->addVotingSite(
            'https://www.liste-serveurs-minecraft.org',
            'Liste Serveurs Minecraft',
            'logos/liste-serveurs-minecraft.org.png',
            'https://www.liste-serveurs-minecraft.org/vote/?idc={id}',
            3,
            \App\VoteValidation\org\liste_serveurs_minecraft\PlayerVoteValidation::class
        );
        $this->addVotingSite(
            'https://serveurs-minecraft.com',
            'Serveurs Minecraft.com',
            'logos/serveurs-minecraft.com',
            'https://www.serveurs-minecraft.com/serveur-minecraft?Classement={id}',
            24,
            \App\VoteValidation\com\serveurs_minecraft\PlayerVoteValidation::class
        );
        $this->addVotingSite(
            'https://www.liste-serveurs.fr',
            'Liste serveurs',
            'logos/liste-serveurs.fr',
            'https://www.liste-serveurs.fr/server-{server-name}.{id}',
            3,
            \App\VoteValidation\fr\liste_serveurs\PlayerVoteValidation::class
        );
        $this->addVotingSite(
            'https://www.liste-minecraft-serveurs.com/',
            'Liste-Minecraft-Serveurs',
            'logos/no-logo',
            'https://www.liste-minecraft-serveurs.com/Serveur/{id}',
            24,
            \App\VoteValidation\com\liste_minecraft_serveurs\PlayerVoteValidation::class
        );
        if (App::environment('local')){
            $this->addVotingSite(
                'http://localhost',
                'Always voted',
                'http://localhost',
                'http://localhost',
                24,
                FakeVoteValidation::class
            );
        }
    }

    private function addVotingSite($url, $name, $logoPublicPath, $serverUrl, $voteLifespan, $validator){
        if (VoteSite::where('url',$url)->first() == null){
            VoteSite::firstOrCreate([
                'url' => $url,
                'name' => $name,
                'logo_public_path' => $logoPublicPath,
                'server_url' => $serverUrl,
                'vote_lifespan'=>$voteLifespan,
                'validator' => $validator
            ]);
        }
    }

}
