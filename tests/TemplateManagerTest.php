<?php

namespace Test;

use App\Context\ApplicationContext;
use App\Entity\Instructor;
use App\Entity\Learner;
use App\Entity\Lesson;
use App\Entity\MeetingPoint;
use App\Entity\Template;
use App\Repository\InstructorRepository;
use App\Repository\LessonRepository;
use App\Repository\MeetingPointRepository;
use App\TemplateManager;
use PHPUnit_Framework_TestCase;

class TemplateManagerTest extends PHPUnit_Framework_TestCase {
	/**
	 * Init the mocks
	 */
	public function setUp(): void {
	}

	/**
	 * Closes the mocks
	 */
	public function tearDown(): void {
		InstructorRepository::clearInstance();
		MeetingPointRepository::clearInstance();
		ApplicationContext::clearInstance();
		LessonRepository::clearInstance();
	}

	public function testGetTemplateComputed_nominalCase_functional(): void {
		$instructorFake = new Instructor(1, "jean", "rock");
		$meetingPointFake = new MeetingPoint(1, "http://lambda.to", "paris 5eme");
		$learnerFake = new Learner(1, "totY", "bob", "toto@bob.to");
		$startAtFake = new \DateTime("2021-01-01 12:00:00");
		$endAtFake = $startAtFake->add(new \DateInterval('PT1H'));
		$lessonFake = new Lesson(1, $meetingPointFake->id, $instructorFake->id, $startAtFake, $endAtFake);

		InstructorRepository::getInstance()
			->save($instructorFake);
		MeetingPointRepository::getInstance()
			->save($meetingPointFake);
		ApplicationContext::getInstance()
			->setCurrentUser($learnerFake);
		LessonRepository::getInstance()
			->save($lessonFake);

		$template = new Template(
			1,
			'Votre leçon de conduite avec [lesson:instructor_name]',
			"\nBonjour [user:first_name],\n\nLa reservation du [lesson:start_date] de [lesson:start_time] à [lesson:end_time] avec [lesson:instructor_name] a bien été prise en compte!\nVoici votre point de rendez-vous: [lesson:meeting_point].\n\nBien cordialement,\n\nL'équipe Ornikar\n");

		$templateManager = new TemplateManager();

		$message = $templateManager->getTemplateComputed(
			$template,
			[
				'lesson' => $lessonFake,
			]
		);

		$learnerFirstnameLoweredAndUCFirst = ucfirst(strtolower($learnerFake->firstname));

		$this->assertEquals("Votre leçon de conduite avec $instructorFake->firstname", $message->subject);
		$this->assertEquals("\nBonjour $learnerFirstnameLoweredAndUCFirst,\n\nLa reservation du {$startAtFake->format('d/m/Y')} de {$startAtFake->format('H:i')} à {$endAtFake->format('H:i')} avec $instructorFake->firstname a bien été prise en compte!\nVoici votre point de rendez-vous: $meetingPointFake->name.\n\nBien cordialement,\n\nL'équipe Ornikar\n", $message->content);
	}
}
