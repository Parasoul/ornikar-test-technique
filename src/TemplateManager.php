<?php

namespace App;

use App\Context\ApplicationContext;
use App\Entity\Learner;
use App\Entity\Lesson;
use App\Entity\Template;
use App\Repository\InstructorRepository;
use App\Repository\MeetingPointRepository;
use App\Repository\Repository;
use App\Transformer\Template\TemplateDataTransformer;

class TemplateManager
{
    private ApplicationContext $applicationContext;
    private Repository $meetingPointRepository;
    private Repository $instructorRepository;
    private TemplateDataTransformer $templateDataTransformer;

    public function __construct(ApplicationContext $applicationContext = null, Repository $meetingPointRepository = null, Repository $instructorRepository = null, TemplateDataTransformer $templateDataTransformer = null)
    {
        $this->applicationContext = $applicationContext ?? ApplicationContext::getInstance();
        $this->meetingPointRepository = $meetingPointRepository ?? MeetingPointRepository::getInstance();
        $this->instructorRepository = $instructorRepository ?? InstructorRepository::getInstance();
        $this->templateDataTransformer = $templateDataTransformer ?? TemplateDataTransformer::getInstance();
    }

    public function getTemplateComputed(Template $template, array $data): Template
    {
        $computedTemplate = clone($template);

        $data['user'] = (isset($data['user']) && ($data['user'] instanceof Learner)) ? $data['user'] : $this->applicationContext->getCurrentUser();

        if (isset($data['lesson']) && $data['lesson'] instanceof Lesson) {
            $data['lessonInstructor'] = $this->instructorRepository->getById($data['lesson']->instructorId);
            $data['lessonMeetingPoint'] = $this->meetingPointRepository->getById($data['lesson']->meetingPointId);
        }

        $transformedData = $this->templateDataTransformer->transformData($data);

        $computedTemplate->subject = str_replace(array_keys($transformedData), array_values($transformedData), $computedTemplate->subject);
        $computedTemplate->content = str_replace(array_keys($transformedData), array_values($transformedData), $computedTemplate->content);

        return $computedTemplate;
    }
}
