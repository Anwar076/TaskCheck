<?php

namespace Tests\Unit;

use App\Enums\SubmissionStatus;
use App\Models\Checklist\TaskList;
use App\Models\Submissions\Submission;
use App\Services\Submissions\SubmissionWorkflowService;
use PHPUnit\Framework\TestCase;

class SubmissionWorkflowServiceTest extends TestCase
{
    private SubmissionWorkflowService $workflow;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workflow = new SubmissionWorkflowService;
    }

    public function test_review_requirement_determines_completion_workflow(): void
    {
        $requiresReview = new TaskList(['requires_review' => true, 'auto_accept_without_review' => false]);
        $noReview = new TaskList(['requires_review' => false, 'auto_accept_without_review' => false]);
        $autoAccept = new TaskList(['requires_review' => false, 'auto_accept_without_review' => true]);

        $this->assertSame(SubmissionStatus::COMPLETED, $this->workflow->statusAfterEmployeeCompletion($requiresReview));
        $this->assertSame(SubmissionStatus::COMPLETED, $this->workflow->statusAfterEmployeeCompletion($noReview));
        $this->assertSame(SubmissionStatus::REVIEWED, $this->workflow->statusAfterEmployeeCompletion($autoAccept));
    }

    public function test_finished_accepted_and_closed_have_one_definition(): void
    {
        $submission = new Submission;

        $submission->status = SubmissionStatus::COMPLETED->value;
        $this->assertTrue($this->workflow->isFinished($submission));
        $this->assertFalse($this->workflow->isAccepted($submission));
        $this->assertFalse($this->workflow->isClosed($submission));

        $submission->status = SubmissionStatus::REVIEWED->value;
        $this->assertTrue($this->workflow->isFinished($submission));
        $this->assertTrue($this->workflow->isAccepted($submission));
        $this->assertTrue($this->workflow->isClosed($submission));

        $submission->status = SubmissionStatus::REJECTED->value;
        $this->assertFalse($this->workflow->isFinished($submission));
        $this->assertFalse($this->workflow->isAccepted($submission));
        $this->assertTrue($this->workflow->isClosed($submission));
    }
}
