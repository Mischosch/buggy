<?php

namespace Buggy\Model;

use Doctrine\ORM\Mapping as ORM,
    Buggy\ModelBase\ProjectBase,
    Buggy\Model\ProjectRepository;

/**
 * @ORM\Entity(repositoryClass="Buggy\Model\ProjectRepository")
 * @ORM\Table(name="project")
 */
class Project extends ProjectBase
{
}
