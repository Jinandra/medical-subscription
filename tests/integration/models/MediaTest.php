<?php
use App\Models\Media;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaTest extends TestCase 
{
	use DatabaseTransactions;

	/** @test */
	function showFeaturedMedia()
	{
		factory(Media::class,3)->create();
		$featured = factory(Media::class)->create(['featured'=>1]);

		$medias = Media::featured();

		$this->assertEquals($featured->id, $medias->last()->id);
	}
}
