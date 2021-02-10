<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <div class="avatar-container">
                    <span class="img-circle content-user-avatar avatar-size-3 content-user-avatar-short-mono "
                          style="background-color: #4595eb;"><?=mb_strtoupper(trim(mb_substr(Yii::$app->user->identity->getNameOrLogin(), 0, 1, 'UTF-8')))?></span>
                </div>
            </div>
            <div class="pull-left info">
                <p><?=!Yii::$app->user->isGuest && Yii::$app->user->identity ? Yii::$app->user->identity->getNameOrLogin() : null ?></p>
            </div>
        </div>

        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Main menu <i class="fas fa-list-ol fa-lg pull-right text-blue"></i>','encode'=>false, 'options' => ['class' => 'header text-blue text-bold']],
                    ['label' => Yii::t('app','Dashboard'), 'icon' => 'tachometer', 'url' => ['site/index']],
                    ['label' => Yii::t('app','News'), 'icon' => 'tachometer', 'url' => ['news/index']],
                    [//Users menu
                        'label' => Yii::t('app', 'Users'),
                        'icon' => 'users',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Users'),
                                'icon' => 'user',
                                'url' => ['/users/index'],
                                'active' => (Yii::$app->controller->id == 'users'),
                                'visible' => \Yii::$app->user->can('users.index')
                            ],
                            [
                                'label' => Yii::t('app', 'Roles admin'),
                                'icon' => 'user-secret',
                                'url' => ['/role'],
                                'active' => (Yii::$app->controller->id == 'role'),
                                'visible' => \Yii::$app->user->can('role.index')
                            ],
                            [
                                'label' => Yii::t('app', 'Permission admin'),
                                'icon' => 'user-secret',
                                'url' => ['/permission/index'],
                                'active' => (Yii::$app->controller->id == 'permission'),
                                'visible' => \Yii::$app->user->can('permission.index'),
                            ]
                        ],
                        'visible' => \Yii::$app->user->can('users.index')
                            || \Yii::$app->user->can('role.index')
                            || \Yii::$app->user->can('permission.index')
                    ],// \End Users menu,
                ],
            ]
        ) ?>

    </section>

</aside>
