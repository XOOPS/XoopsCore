<h4>Description</h4>
<p>
    Plugins are module components that extend functionality of other modules.
    For example, a Search module might use a News plugin to search for news.
    A News module might use a Tags plugin for tags, or a Comments plugin for comments.
</p>
<br>
<h4>Callers and Listeners</h4>
<p>
    In the above example, the search module is a <strong>caller</strong> and the news module is a <strong>listener</strong>.
    The News module is also a <strong>caller</strong> having the Tag module as a <strong>listener</strong>.
</p>
<br>
<h4>Activation and Ordering</h4>
<p>
    The Plugins module allows you to deactivate this relationships between callers and listeners and to choose the order you want the listeners to respond.
    Relationships with lower values will execute first. By default, this value is set to 0. If you want some relationship to happen last, just give it the higher value.
</p>