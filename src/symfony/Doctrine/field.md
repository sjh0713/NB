## Entity数据设置
- Entiy中table设置     

        /**
        * @ORM\Entity
        * @ORM\Table(name="application", options={"comment":"Funding applications"});
        */
        
        /**
        * @Entity
        * @Table(name="user",
        *  uniqueConstraints={@UniqueConstraint(name="user_unique",columns={"username"})},
        *  indexes={@Index(name="user_idx", columns={"email"})}
        *  schema="schema_name"
        * )
        */
        class User { }
- Entity的filed设置

        @ORM\Column(name="filed_name", type="string", length=64, nullable=true, options={"default" : "default_value", "comment": "字段注释","fixed": true})

    - default设置字段的默认值
    - nullable设置是否可以为null
    - comment字段的注释
    - fixed=true,把varchar=>char
    - type="string"
        - length= 255(2 ^ 8 - 1),TINYTEXT
        - length= 65535(2 ^ 16 - 1),TEXT
        - length= 16777215(2 ^ 24 - 1),MEDIUMTEXT
        - length= 4294967295(2 ^ 32 - 1),LONGTEXT

- Doctrine filed types（decimal浮点型）
    
        /**
        * @var int
        *
        * @ORM\Column(name="longitude", type="decimal", precision=8, scale=4, options={"comment":"经度"})
        */
        private $longitude;
        
### Doctrine Uuid Type

>https://github.com/ramsey/uuid-doctrine

- config.yml

        # app/config/config.yml
        doctrine:
           dbal:
               types:
                   uuid:  Ramsey\Uuid\Doctrine\UuidType

- Usage Then, in your models, you may annotate properties by setting the @Column type to uuid, and defining a custom generator of Ramsey\Uuid\UuidGenerator. Doctrine will handle the rest.
use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="products")
     */
    class Product
    {
        /**
         * @var \Ramsey\Uuid\UuidInterface
         *
         * @ORM\Id
         * @ORM\Column(type="uuid", unique=true)
         * @ORM\GeneratedValue(strategy="CUSTOM")
         * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
         */
        protected $id;
    
        public function getId()
        {
            return $this->id;
        }
    }
