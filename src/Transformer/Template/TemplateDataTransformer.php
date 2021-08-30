<?php

namespace App\Transformer\Template;

use App\Entity\Instructor;
use App\Entity\Learner;
use App\Entity\Lesson;
use App\Entity\MeetingPoint;
use App\Helper\SingletonTrait;
use App\Utils\TextUtils;

class TemplateDataTransformer
{
    use SingletonTrait;

    public function transformData(array $data): array
    {
        // Default value of placeholders
        $transformedData = [
            '[instructor_link]' => '',
        ];

        if (isset($data['lesson']) && $data['lesson'] instanceof Lesson) {
            $transformedData = array_merge($transformedData, $this->transformLessonData($data['lesson']));
        }

        if (isset($data['lessonMeetingPoint']) && $data['lessonMeetingPoint'] instanceof MeetingPoint) {
            $transformedData = array_merge($transformedData, $this->transformLessonMeetingPointData($data['lessonMeetingPoint']));
        }

        if (isset($data['lessonInstructor']) && $data['lessonInstructor'] instanceof Instructor) {
            $transformedData = array_merge($transformedData, $this->transformLessonInstructorData($data['lessonInstructor']));
        }

        if (isset($data['instructor']) && $data['instructor'] instanceof Instructor) {
            $transformedData = array_merge($transformedData, $this->transformInstructorData($data['instructor']));
        }

        if (isset($data['user']) && $data['user'] instanceof Learner) {
            $transformedData = array_merge($transformedData, $this->transformLearnerData($data['user']));
        }

        return $transformedData;
    }

    private function transformLessonData(Lesson $lesson): array
    {
        return [
            '[lesson:summary_html]' => Lesson::renderHtml($lesson),
            '[lesson:summary]' => Lesson::renderText($lesson),
            '[lesson:start_date]' => $lesson->start_time->format('d/m/Y'),
            '[lesson:start_time]' => $lesson->start_time->format('H:i'),
            '[lesson:end_time]' => $lesson->end_time->format('H:i'),
        ];
    }

    private function transformLessonMeetingPointData(MeetingPoint $meetingPoint): array
    {
        return [
            '[lesson:meeting_point]' => $meetingPoint->name,
        ];
    }

    private function transformLessonInstructorData(Instructor $instructor): array
    {
        return [
            '[lesson:instructor_name]' => TextUtils::getStringLoweredAndUCFirst($instructor->firstname),
            '[lesson:instructor_link]' => $instructor->getLink(),
        ];
    }

    private function transformInstructorData(Instructor $instructor): array
    {
        return [
            '[instructor_link]' => $instructor->getLink(),
        ];
    }

    private function transformLearnerData(Learner $leaner): array
    {
        return [
            '[user:first_name]' => TextUtils::getStringLoweredAndUCFirst($leaner->firstname),
        ];
    }
}
