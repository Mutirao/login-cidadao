<?php
/*
 * This file is part of the login-cidadao project or it's bundles.
 *
 * (c) Guilherme Donato <guilhermednt on github>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LoginCidadao\CoreBundle\Command;

use LoginCidadao\CoreBundle\Helper\UserManipulatorProxy;
use Symfony\Component\Console\Output\OutputInterface;
use FOS\UserBundle\Util\UserManipulator;

class PromoteUserCommand extends \FOS\UserBundle\Command\PromoteUserCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('lc:user:promote')
            ->setDescription('Promotes a user by email by adding a role')
            ->setHelp(<<<EOT
The <info>lc:user:promote</info> command promotes a user by adding a role

  <info>php %command.full_name% matthieu@email.com ROLE_CUSTOM</info>
  <info>php %command.full_name% --super matthieu@email.com</info>
EOT
            );
    }

    protected function executeRoleCommand(UserManipulator $manipulator, OutputInterface $output, $username, $super, $role)
    {
        /** @var UserManipulatorProxy $manipulator */
        $manipulatorProxy = $this->getContainer()->get('lc.fos.user_manipulator.proxy');
        parent::executeRoleCommand($manipulatorProxy, $output, $username, $super, $role);
    }
}
