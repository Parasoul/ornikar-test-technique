<?php

namespace App;

use App\Context\ApplicationContext;
use App\Entity\Instructor;
use App\Entity\Learner;
use App\Entity\Lesson;
use App\Entity\Template;
use App\Repository\InstructorRepository;
use App\Repository\LessonRepository;
use App\Repository\MeetingPointRepository;
use App\Repository\Repository;

class TemplateManager
{
    private ApplicationContext $applicationContext;
    private Repository $lessonRepository;
    private Repository $meetingPointRepository;
    private Repository $instructorRepository;

    public function __construct(ApplicationContext $applicationContext = null, Repository $lessonRepository = null, Repository $meetingPointRepository = null, Repository $instructorRepository = null)
    {
        $this->applicationContext = $applicationContext ?? ApplicationContext::getInstance();
        $this->lessonRepository = $lessonRepository ?? LessonRepository::getInstance();
        $this->meetingPointRepository = $meetingPointRepository ?? MeetingPointRepository::getInstance();
        $this->instructorRepository = $instructorRepository ?? InstructorRepository::getInstance();
    }

    public function getTemplateComputed(Template $template, array $data): Template
    {
        $replaced = clone($template);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        //Replace $data['lesson'] PlaceHolders
        $lesson = (isset($data['lesson']) && $data['lesson'] instanceof Lesson) ? $data['lesson'] : null;
        if ($lesson) {
            $lesson = $this->lessonRepository->getById($lesson->id);
            $lessonMeetingPoint = $this->meetingPointRepository->getById($lesson->meetingPointId);
            $lessonInstructor = $this->instructorRepository->getById($lesson->instructorId);

            if (strpos($text, '[lesson:instructor_link]') !== false) {
                $lessonInstructorLink = 'instructors/' . $lessonInstructor->id . '-' . urlencode($lessonInstructor->firstname);
                $text = str_replace('[lesson:instructor_link]', $lessonInstructorLink, $text);
            }

            if (strpos($text, '[lesson:summary_html]') !== false) {
                $text = str_replace(
                    '[lesson:summary_html]',
                    Lesson::renderHtml($lesson),
                    $text
                );
            }

            if (strpos($text, '[lesson:summary]') !== false) {
                $text = str_replace(
                    '[lesson:summary]',
                    Lesson::renderText($lesson),
                    $text
                );
            }

            if (strpos($text, '[lesson:instructor_name]') !== false) {
                $text = str_replace('[lesson:instructor_name]', $lessonInstructor->firstname, $text);
            }

            if (strpos($text, '[lesson:meeting_point]') !== false) {
                $text = str_replace('[lesson:meeting_point]', $lessonMeetingPoint->name, $text);
            }

            if (strpos($text, '[lesson:start_date]') !== false) {
                $text = str_replace('[lesson:start_date]', $lesson->start_time->format('d/m/Y'), $text);
            }

            if (strpos($text, '[lesson:start_time]') !== false) {
                $text = str_replace('[lesson:start_time]', $lesson->start_time->format('H:i'), $text);
            }

            if (strpos($text, '[lesson:end_time]') !== false) {
                $text = str_replace('[lesson:end_time]', $lesson->end_time->format('H:i'), $text);
            }
        }

        // Replace $data['instructor'] PlaceHolders
        $lessonInstructorLink = null;
        if (isset($data['instructor']) && ($data['instructor'] instanceof Instructor)) {
            $lessonInstructorLink = 'instructors/' . $data['instructor']->id . '-' . urlencode($data['instructor']->firstname);
        }
        $text = str_replace('[instructor_link]', $lessonInstructorLink, $text);

        // Replace $data['user'] PlaceHolders
        $user = (isset($data['user']) && ($data['user'] instanceof Learner)) ? $data['user'] : $this->applicationContext->getCurrentUser();
        if ($user && strpos($text, '[user:first_name]') !== false) {
            $text = str_replace('[user:first_name]', ucfirst(strtolower($user->firstname)), $text);
        }

        return $text;
    }
}
