<?php

class LoginController extends Controller implements ControllerInterface
{
  private LoginUseCase $loginService;

  public function __construct(LoginUseCase $loginService)
  {
    parent::__construct();
    $this->loginService = $loginService;
  }

  protected function perform(
    LoginUseCaseInput $request
  ): ControllerOutputType {
    $output =  $this->loginService->execute($request);
    if ($output instanceof Error) {
      return badRequest($output);
    }
    return ok((object)[
      'token' => $output->token,
      'user' => (object)[
        'id' => $output->user->id,
        'name' => $output->user->name,
        'email' => $output->user->email,
        'admin' => $output->user->admin,
      ]
    ]);
  }

  protected function getValidation(): ValidatorInterface
  {
    return new ValidatorComposite([
      (new ValidatorBuilder())->of("email")->isRequired(),
      (new ValidatorBuilder())->of("email")->isType(FieldTypeEnum::STRING),
      (new ValidatorBuilder())->of("password")->isRequired(),
      (new ValidatorBuilder())->of("password")->isType(FieldTypeEnum::STRING),
    ]);
  }
}
