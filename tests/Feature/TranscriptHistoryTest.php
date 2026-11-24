<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranscriptHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_search_their_transcripts_by_student_name_nim_or_transcript_number(): void
    {
        $user = User::factory()->create();
        $matchingTranscript = $user->transcripts()->create([
            'student_name' => 'Budi Santoso',
            'student_nim' => '123456',
            'header' => ['no_transkrip' => '0099/S1/2026'],
            'pejabat' => [],
            'mata_kuliah' => [],
        ]);
        $user->transcripts()->create([
            'student_name' => 'Siti Aminah',
            'student_nim' => '654321',
            'header' => ['no_transkrip' => '0010/S1/2026'],
            'pejabat' => [],
            'mata_kuliah' => [],
        ]);

        $this->actingAs($user)
            ->get(route('transcripts.index', ['search' => '0099']))
            ->assertOk()
            ->assertSee($matchingTranscript->student_name)
            ->assertDontSee('Siti Aminah');
    }

    public function test_users_can_delete_only_their_own_transcripts(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $transcript = $user->transcripts()->create([
            'student_name' => 'Budi Santoso',
            'student_nim' => '123456',
            'header' => ['no_transkrip' => '0099/S1/2026'],
            'pejabat' => [],
            'mata_kuliah' => [],
        ]);
        $otherTranscript = $otherUser->transcripts()->create([
            'student_name' => 'Siti Aminah',
            'student_nim' => '654321',
            'header' => ['no_transkrip' => '0010/S1/2026'],
            'pejabat' => [],
            'mata_kuliah' => [],
        ]);

        $this->actingAs($user)
            ->delete(route('transcripts.destroy', $transcript))
            ->assertRedirect(route('transcripts.index'));

        $this->assertDatabaseMissing('transcripts', ['id' => $transcript->id]);
        $this->assertDatabaseHas('transcripts', ['id' => $otherTranscript->id]);
    }

    public function test_users_cannot_delete_another_users_transcript(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $transcript = $otherUser->transcripts()->create([
            'student_name' => 'Siti Aminah',
            'student_nim' => '654321',
            'header' => ['no_transkrip' => '0010/S1/2026'],
            'pejabat' => [],
            'mata_kuliah' => [],
        ]);

        $this->actingAs($user)
            ->delete(route('transcripts.destroy', $transcript))
            ->assertNotFound();

        $this->assertDatabaseHas('transcripts', ['id' => $transcript->id]);
    }
}
