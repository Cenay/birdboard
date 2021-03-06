<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Project;
use Facades\Tests\Setup\ProjectTestFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\Concerns;
use phpDocumentor\Reflection\ProjectFactory;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_projects()
    {
        // Given that we have a project
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');           // ASSERTION
        $this->get('/projects/create')->assertRedirect('login');    // ASSERTION
        $this->get($project->path())->assertRedirect('login');      // ASSERTION
        $this->get($project->path() . '/edit')->assertRedirect('login');      // ASSERTION
        $this->post('/projects', $project->toArray())->assertRedirect('login'); // ASSERTION
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $this->signIn();        // Helper method in the TestCase.php class

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(4),
            'notes' => 'General notes'
        ];

        //$this->post('/projects', $attributes)->assertRedirect('/projects/1'); // ASSERTION
        // Another method (see for above)
        $response = $this->post('/projects', $attributes);      // Create the project
        $project = Project::where($attributes)->first();       // Track down the created project

        $response->assertRedirect($project->path());            // We got redirected to the proper place

        $this->get($project->path())                            // When we visit the project page
             ->assertSee($attributes['title'])                   // we should see these things
             ->assertSee($attributes['description'])             //
             ->assertSee($attributes['notes']);                  //
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        //$this->withoutExceptionHandling();

        // Given we're signed in
        $user = $this->signIn();

        // and we've been invited to a project that was not created by us
        // $project = ProjectTestFactory::create();
        // $project->invite($user);
        // Can be replaced with this.
        $project = tap(ProjectTestFactory::create())->invite($user);

        // when I visit my dashboard
        // I should see that project
        $this->get('/projects')
            ->assertSee($project->title);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_projects()
    {
        // Arrange
        $project = ProjectTestFactory::create();

        // Act (Assert)
        $this->delete($project->path())
             ->assertRedirect('/login');

        // Arrange differently
        $this->signIn();

        // Act (assert)
        $this->delete($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectTestFactory::create();

        // Attempt the update
        $this->actingAs($project->owner)
             ->delete($project->path())
             ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectTestFactory::create();

        // Attempt the update
        $this->actingAs($project->owner)
             ->patch($project->path(), $attributes = ['title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'])
             ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        // Validate our update made it
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_update_a_projects_general_notes()
    {
        $project = ProjectTestFactory::create();

        // Attempt the update
        $this->actingAs($project->owner)
             ->patch($project->path(), $attributes = ['notes' => 'Changed']);

        // Validate our update made it
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $project = ProjectTestFactory::create();

        $this->actingAs($project->owner)
             ->get($project->path())            // Act
             ->assertSee($project->title)       // Assert
             ->assertSee($project->description)
             ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();        // Helper method in the TestCase.php class

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);    // ASSERTION
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title'); // ASSERTION
    }

    /** @test
     * These were working until I cleared the config:cache
    */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');   // ASSERTION
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->signIn();        // Helper method in the TestCase.php class

        $project = factory('App\Project')->create();

        $this->patch($project->path(), [])->assertStatus(403);    // ASSERTION
    }
}
