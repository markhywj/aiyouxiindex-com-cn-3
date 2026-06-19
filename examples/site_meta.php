<?php

/**
 * 站点元信息管理示例
 * 用于集中管理站点关键数据并生成 SEO 友好的描述文本
 */

class SiteMetaInfo
{
    /**
     * @var array 站点基本信息集合
     */
    private array $metaSet;

    /**
     * @param array $initialData 初始数据，可选
     */
    public function __construct(array $initialData = [])
    {
        $this->metaSet = $initialData;
    }

    /**
     * 添加或更新一条元信息
     * @param string $key 键名
     * @param mixed $value 值
     */
    public function set(string $key, $value): void
    {
        $this->metaSet[$key] = $value;
    }

    /**
     * 获取指定键的元数据，若不存在返回默认值
     * @param string $key 键名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->metaSet[$key] ?? $default;
    }

    /**
     * 基于当前元数据生成简短描述文本
     * 输出格式可自定义，默认包含站点名称、关键词及 URL 信息
     * @return string
     */
    public function generateDescription(): string
    {
        $parts = [];

        $title = $this->get('title', '');
        $keywords = $this->get('keywords', []);
        $url = $this->get('url', '');
        $tagline = $this->get('tagline', '');

        if ($title !== '') {
            $parts[] = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        }

        if ($tagline !== '') {
            $parts[] = htmlspecialchars($tagline, ENT_QUOTES, 'UTF-8');
        }

        if (!empty($keywords) && is_array($keywords)) {
            $kwStr = implode('、', array_map(function ($kw) {
                return htmlspecialchars((string)$kw, ENT_QUOTES, 'UTF-8');
            }, $keywords));
            $parts[] = '关键词：' . $kwStr;
        }

        if ($url !== '') {
            $parts[] = '访问：' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        }

        return implode(' | ', $parts);
    }

    /**
     * 获取全部元数据
     * @return array
     */
    public function getAll(): array
    {
        return $this->metaSet;
    }

    /**
     * 从数组加载数据（合并）
     * @param array $data
     */
    public function loadFromArray(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * 输出 HTML 友好的 meta 标签（简单示例）
     * @return string
     */
    public function toMetaTags(): string
    {
        $desc = $this->generateDescription();
        $output = '<meta name="description" content="' . htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') . '" />' . "\n";

        $keywords = $this->get('keywords', []);
        if (!empty($keywords)) {
            $kwStr = implode(', ', array_map('htmlspecialchars', $keywords));
            $output .= '<meta name="keywords" content="' . $kwStr . '" />' . "\n";
        }

        return $output;
    }
}

// ---------- 示例使用 ----------

$siteMeta = new SiteMetaInfo();

$siteMeta->loadFromArray([
    'title'    => '爱游戏玩家社区',
    'tagline'  => '发现最好玩的游戏世界',
    'keywords' => ['爱游戏', '游戏评测', '社区', '攻略'],
    'url'      => 'https://aiyouxiindex.com.cn',
    'language' => 'zh-CN',
]);

echo $siteMeta->generateDescription() . "\n";

echo $siteMeta->toMetaTags();